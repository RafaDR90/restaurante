<?php

namespace App\Controller;

use App\Repository\RestauranteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Pedidos;
use App\Entity\DatosPedido;
use App\Repository\DatosPedidoRepository;
use App\Repository\PedidosRepository;


class PedidoController extends AbstractController
{
    #[Route('/pedido', name: 'app_pedido')]
    public function index(PedidosRepository $pedidosRepository, DatosPedidoRepository $datosPedidoRepository,RestauranteRepository $restauranteRepository): Response
    {
        //comprueba que soy admin con isGranted sino redirigo a categorias
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_categorias_index');
        }

        $pedidos = $pedidosRepository->findBy([], ['id' => 'desc']);

        foreach ($pedidos as $pedido) {
            $productos = $datosPedidoRepository->findBy(['pedido' => $pedido->getId()]);
            $pedido->productos = $productos;
        }
        $totalPrecio = 0;
        foreach ($pedidos as $pedido) {
            foreach ($pedido->productos as $producto) {
                $totalPrecio += $producto->getProducto()->getPrecio() * $producto->getUnidades();
            }
            $pedido->totalPrecio = $totalPrecio;
            $totalPrecio = 0;
        }
        //añadi a pedido su usuario
        foreach ($pedidos as $pedido) {
            $usuarioId = $pedido->getRestaurante();
            $usuario = $restauranteRepository->find($usuarioId);
            $pedido->usuario = $usuario->getEmail();
        }

        return $this->render('pedido/index.html.twig', [
            'controller_name' => 'PedidoController',
            'pedidos' => $pedidos,
            'error' => $_GET['error'] ?? null,
            'exito' => $_GET['exito'] ?? null,
        ]);
    }

    #[Route('/mis_pedidos', name: 'app_pedido_mis_pedidos')]
    public function misPedidos(PedidosRepository $pedidosRepository, DatosPedidoRepository $datosPedidoRepository)
    {
        //comprueba que estoy logueado, sino me redirige a login
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $pedidos = $pedidosRepository->findBy(['restaurante' => $this->getUser()->getId()], ['id' => 'desc']);


        foreach ($pedidos as $pedido) {
            $productos = $datosPedidoRepository->findBy(['pedido' => $pedido->getId()]);
            $pedido->productos = $productos;
        }
        $totalPrecio = 0;
        foreach ($pedidos as $pedido) {
            foreach ($pedido->productos as $producto) {
                $totalPrecio += $producto->getProducto()->getPrecio() * $producto->getUnidades();
            }
            $pedido->totalPrecio = $totalPrecio;
            $totalPrecio = 0;
        }

        return $this->render('pedido/misPedidos.html.twig', [
            'controller_name' => 'PedidoController',
            'pedidos' => $pedidos,
            'error' => $_GET['error'] ?? null,
            'exito' => $_GET['exito'] ?? null,
        ]);
    }


    #[Route('/marcar_enviado/{id}', name: 'marcar_enviado')]
     public function marcarEnviado(Pedidos $pedido, PedidosRepository $pedidosRepository, EntityManagerInterface $entityManager, $id)
    {
        //comprueba que soy admin con isGranted sino redirigo a categorias
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_categorias_index', ['error' => 'No puedes enviar un pedido si no eres admin']);
        }

        if ($pedido->getEnviado() == 2) {
            return $this->redirectToRoute('app_pedido', ['error' => 'No puedes marcar como enviado un pedido que ya ha sido cancelado']);
        }if ($pedido->getEnviado() == 1) {
            return $this->redirectToRoute('app_pedido', ['error' => 'No puedes marcar como enviado un pedido que ya ha sido enviado']);
        }

        $pedido->setEnviado(1);
        $entityManager->flush();
        return $this->redirectToRoute('app_pedido', ['exito' => 'Pedido enviado']);
    }

    #[Route('/marcar_cancelado/{id}', name: 'marcar_cancelado')]
    public function marcar_cancelado(Pedidos $pedido, PedidosRepository $pedidosRepository, EntityManagerInterface $entityManager, $id)
    {
        //comprueba que soy admin con isGranted sino redirigo a categorias
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_categorias_index', ['error' => 'No puedes cancelar un pedido si no eres admin']);
        }

        if ($pedido->getEnviado() == 2) {
            return $this->redirectToRoute('app_pedido', ['error' => 'No puedes cancelar un pedido que ya ha sido cancelado']);
        }if ($pedido->getEnviado() == 1) {
        return $this->redirectToRoute('app_pedido', ['error' => 'No puedes cancelar un pedido que ya ha sido enviado']);
    }

        $pedido->setEnviado(2);
        $entityManager->flush();
        return $this->redirectToRoute('app_pedido', ['exito' => 'Pedido cancelado']);
    }

    #[Route('/marcar_cancelado_mipedido/{id}', name: 'marcar_cancelado_mipedido')]
    public function marcar_cancelado_mipedido(Pedidos $pedido, PedidosRepository $pedidosRepository, EntityManagerInterface $entityManager, $id)
    {

        //comprueba que el user id es = al pedido restaurante
        if ($this->getUser()->getId() != $pedido->getRestaurante()->getId()) {
            return $this->redirectToRoute('app_pedido_mis_pedidos', ['error' => 'No puedes cancelar un pedido que no es tuyo']);
        }

        //comprueba que el pedido es igual a 0
        if ($pedido->getEnviado() == 2) {
            return $this->redirectToRoute('app_pedido_mis_pedidos', ['error' => 'No puedes cancelar un pedido que ya ha sido cancelado']);
        }elseif ($pedido->getEnviado() == 1) {
            return $this->redirectToRoute('app_pedido_mis_pedidos', ['error' => 'No puedes cancelar un pedido que ya ha sido enviado']);
        }

        $pedido->setEnviado(2);
        $entityManager->flush();
        return $this->redirectToRoute('app_pedido_mis_pedidos', ['exito' => 'Pedido cancelado']);
    }



}
