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
        $pedidos= $pedidosRepository->findAll();
        //añade a cada pedido los productos que contiene
        foreach ($pedidos as $pedido) {
            $pedido->productos = $datosPedidoRepository->findBy(['pedido' => $pedido->getId()]);
        }
        return $this->render('pedido/index.html.twig', [
            'controller_name' => 'PedidoController',
            'pedidos' => $pedidos,
        ]);
    }
}
