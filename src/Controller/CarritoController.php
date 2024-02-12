<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Productos;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\EmailController;


class CarritoController extends AbstractController
{

    #[Route('/carrito_comprar', name: 'carrito_comprar')]
    public function carrito_comprar( SessionInterface $session, ProductosRepository $productosRepository, EntityManagerInterface $entityManager,EmailController $emailController)
    {

        //compruba que hay stock de todos los productos de la sesion cart
        $carrito = $session->get('cart', []);
        $productos_id = array_keys($carrito);
        $productos = $productosRepository->findBy(['id' => $productos_id]);
        foreach ($productos as $producto) {
            if ($carrito[$producto->getId()] > $producto->getStock()) {
                return $this->redirectToRoute('app_carrito', ['error' => 'No hay stock suficiente']);
            }
            //resta el stock de los productos
            foreach ($productos as $producto) {
                $producto->setStock($producto->getStock() - $carrito[$producto->getId()]);
                $producto->cantidad = $carrito[$producto->getId()];
                //guarda los cambios en la base de datos
            }
            //guarda los cambios en la base de datos
            $entityManager->flush();

            //vacía el carrito
            $session->set('cart', []);
            //envia el email
            $htmlContent = $emailController->crearHTML($productos);
            $emailController->sendEmail($htmlContent);
            return $this->redirectToRoute('app_carrito', ['exito' => 'Compra realizada con éxito']);
        }
    }

    #[
        Route('/carrito', name: 'app_carrito')]
    public function index(SessionInterface $session, ProductosRepository $productosRepository): Response
    {
        //obtiene los id de productos de la sesion
        $carrito = $session->get('cart', []);
        //calculo el numero de productos
        $total = 0;
        foreach ($carrito as $id => $cantidad) {
            $total += $cantidad;
        }

        $productos_id = array_keys($carrito);

        //obtiene los productos de la base de datos
        $productos = $productosRepository->findBy(['id' => $productos_id]);
        //añado a productos la cantidad de cada producto
        foreach ($productos as $producto) {
            $producto->cantidad = $carrito[$producto->getId()];
        }


        return $this->render('carrito/index.html.twig', [
            'controller_name' => 'CarritoController',
            'productos' => $productos,
            'total' => $total,
        ]);
    }

    #[Route('/carrito/add/{id}', name: 'carrito_agregar')]
    public function add($id, SessionInterface $session, ProductosRepository $productosRepository): Response
    {
        //obtengo el producto por el id
        $producto = $productosRepository->find($id);
        //obtiene los productos de la sesion
        $carrito = $session->get('cart', []);
        //comprueba stock y añade el producto al carrito si hay stock
        if ($carrito[$id] < $producto->getStock())
            $carrito[$id]++;
        //guarda el carrito en la sesion
        $session->set('cart', $carrito);
        return $this->redirectToRoute('app_carrito');
    }

    #[Route('/carrito/remove/{id}', name: 'carrito_quitar')]
    public function remove($id, SessionInterface $session): Response
    {
        //obtiene los productos de la sesion
        $carrito = $session->get('cart', []);
        //si queda 1 o menos unidades, elimina el producto del carrito
        if ($carrito[$id] <= 1)
            unset($carrito[$id]);
        else
            $carrito[$id]--;
        //guarda el carrito en la sesion
        $session->set('cart', $carrito);
        return $this->redirectToRoute('app_carrito');
    }
}
