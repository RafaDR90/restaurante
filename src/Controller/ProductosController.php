<?php

namespace App\Controller;

use App\Repository\CategoriasRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Productos;
use App\Form\ProductosType;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorias/{catId}/productos')]
class ProductosController extends AbstractController
{
    #[Route('/products', name: 'app_product', methods: ['GET', 'POST'])]
    public function products(ProductosRepository $productosRepository,CategoriasRepository $categoriaRepository)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['categoriaId'])) {
            $productos=$productosRepository->findBy(['categoria' => $_POST['categoriaId']]);
            $categorias=$categoriaRepository->findBy(['id' => $_POST['categoriaId']]);
        } else {
            $productos=$productosRepository->findAll();
            $categorias=$categoriaRepository->findAll();
        }



        return $this->render('productos/products.html.twig', [
            'productos' => $productos,
            'categorias' => $categorias,
            'error' => $_GET['error'] ?? null,
            'exito' => $_GET['exito'] ?? null,
        ]);

    }
    #[Route('/new', name: 'app_productos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$catId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_product', ['error' => 'Permisos insuficientes','catId'=>0]);
        }
        $producto = new Productos();
        $form = $this->createForm(ProductosType::class, $producto, ['catId' => $catId]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($producto);
            $entityManager->flush();
            return $this->redirectToRoute('app_productos_index', ['catId'=>$catId, 'exito'=>'Producto creado'], Response::HTTP_SEE_OTHER);
        }

        return $this->render('productos/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
            'catId' => $catId,

        ]);
    }


    #[Route('/', name: 'app_productos_index', methods: ['GET'])]
    public function index(ProductosRepository $productosRepository,CategoriasRepository $categoriaRepository,$catId ): Response
    {

        //obtengo nombre de la categoria
        $catNombre=$categoriaRepository->find($catId)->getNombre();
        //obtiene productos con id de categoria
        $productos=$productosRepository->findBy(['categoria' => $catId]);
        return $this->render('productos/index.html.twig', [
            'productos' => $productos,
            'catId' => $catId,
            'nombre' => $catNombre,
            'error' => $_GET['error'] ?? null,
            'exito' => $_GET['exito'] ?? null,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_productos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Productos $producto, EntityManagerInterface $entityManager,$catId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_product', ['error' => 'Permisos insuficientes','catId'=>0]);
        }
        $form = $this->createForm(ProductosType::class, $producto, ['catId' => $catId]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_productos_index', ['catId'=>$catId,'exito'=>'Producto editado'], Response::HTTP_SEE_OTHER);
        }

        return $this->render('productos/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
            'catId' => $catId,
        ]);
    }

    #[Route('/{id}', name: 'app_productos_delete', methods: ['POST'])]
    public function delete(Request $request, Productos $producto, EntityManagerInterface $entityManager,$catId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_product', ['error' => 'Permisos insuficientes','catId'=>0]);
        }
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $producto->setStock(0);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_productos_index', ['catId'=>$catId, 'exito'=>'Producto eliminado'], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/add', name: 'app_productos_addCart', methods: ['GET'])]
    public function addCart(Request $request, Productos $producto, SessionInterface $session): Response
    {

        $cart = $session->get('cart', []);
        $id = $producto->getId();
        if (!empty($cart[$id])) {
            //comprueba que exista stock para añadir uno mas
            if($cart[$id]<$producto->getStock())
            $cart[$id]++;
        } else {
            //comprueba que exista stock para añadir uno
            if($producto->getStock()>0)
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute('app_carrito', ['catId'=>$producto->getCategoria()->getId()], Response::HTTP_SEE_OTHER);
    }

}
