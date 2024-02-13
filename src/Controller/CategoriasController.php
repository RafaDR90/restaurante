<?php

namespace App\Controller;

use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Categorias;
use App\Form\CategoriasType;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorias')]
class CategoriasController extends AbstractController
{


    #[Route('/{id}/acceso', name: 'app_categorias_acceso', methods: ['GET'])]
    public function productos(Categorias $categoria): Response
    {
        //comprueba si estoy logueado
        if($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }
        //redirige a productosController con la id de la categoria
        return $this->redirectToRoute('app_productos_index', ['catId' => $categoria->getId()]);
    }

    #[Route('/', name: 'app_categorias_index', methods: ['GET'])]
    public function index(CategoriasRepository $categoriasRepository): Response
    {
        //si esta logueado
        if($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }else{
            return $this->render('categorias/index.html.twig', [
                'categorias' => $categoriasRepository->findAll(),
                'error' => $_GET['error'] ?? null,
                'exito' => $_GET['exito'] ?? null,
            ]);
        }
    }

    #[Route('/new', name: 'app_categorias_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoria = new Categorias();
        $form = $this->createForm(CategoriasType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoria);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorias_index', ['exito'=>'Categoria creada'], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorias/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorias_show', methods: ['GET'])]
    public function show(Categorias $categoria): Response
    {
        return $this->render('categorias/show.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorias_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorias $categoria, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriasType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorias_index', ['exito'=>'Categoria editada'], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorias/edit.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorias_delete', methods: ['POST'])]
    public function delete(Request $request, Categorias $categoria, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoria->getId(), $request->request->get('_token'))) {
            // pone todos los productos con nombre "Sin Categoria"
            $productos = $categoria->getProductos();
            foreach ($productos as $producto) {
                $producto->setCategoria($entityManager->getReference(Categorias::class, 1));
            }
            $entityManager->flush();
            $entityManager->remove($categoria);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorias_index', ['exito'=>'Categoria eliminada'], Response::HTTP_SEE_OTHER);
    }


}
