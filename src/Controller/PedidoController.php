<?php

namespace App\Controller;

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
    public function index(PedidosRepository $pedidosRepository, DatosPedidoRepository $datosPedidoRepository): Response
    {
        //comprueba que soy admin con isGranted sino redirigo a categorias
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_categorias_index');
        }
        $pedidos = $pedidosRepository->findAll();
        //añade a cada pedido los productos que contiene
        foreach ($pedidos as $pedido) {
            $pedido->productos = $datosPedidoRepository->findBy(['pedido' => $pedido->getId()]);
        }
        return $this->render('pedido/index.html.twig', [
            'controller_name' => 'PedidoController',
            'pedidos' => $pedidos,
        ]);
    }

    #[Route('/mis_pedidos', name: 'app_pedido_mis_pedidos')]
    public function misPedidos(PedidosRepository $pedidosRepository, DatosPedidoRepository $datosPedidoRepository)
    {
        //comprueba que estoy logueado, sino me redirige a login
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $pedidos = $pedidosRepository->findBy(['restaurante' => $this->getUser()]);
        foreach ($pedidos as $pedido) {
            $pedido->productos = $datosPedidoRepository->findBy(['pedido' => $pedido->getId()]);
        }
        return $this->render('pedido/misPedidos.html.twig', [
            'controller_name' => 'PedidoController',
            'pedidos' => $pedidos,
        ]);
    }
}
