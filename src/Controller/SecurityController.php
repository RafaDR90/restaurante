<?php

namespace App\Controller;

use App\Entity\Restaurante;
use App\Form\EditarPerfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_redirect')]
    public function redirection(): Response
    {
        return $this->redirectToRoute('app_login');
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if ($this->getUser())
        return $this->redirectToRoute('app_product', ['catId' => 0]);
        else
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    #[Route(path: '/user/{id}/edit', name: 'user_edit')]
    public function edit(Request $request, Restaurante $restaurante, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Obtener la contraseña original
        $originalPassword = $restaurante->getPassword();

        // Crear el formulario con el objeto Restaurante
        $form = $this->createForm(EditarPerfilType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if(!$form->isValid()){
                $error = "Error al guardar los cambios";
            } else {
                // Obtener la contraseña del formulario
                $newPassword = $form->get('password')->getData();

                // Verificar si la nueva contraseña está vacía
                if ($newPassword !== null) {
                    if (strlen($newPassword)<6){
                        $error = "La contraseña debe tener al menos 6 caracteres";
                        return $this->render('security/edit.html.twig', [
                            'form' => $form->createView(),
                            'error' => $error ?? null,
                        ]);
                    }
                    // Codificar la nueva contraseña
                    $encodedPassword = $userPasswordHasher->hashPassword($restaurante, $newPassword);

                    // Establecer la nueva contraseña codificada
                    $restaurante->setPassword($encodedPassword);
                } else {
                    // Restaurar la contraseña original
                    $restaurante->setPassword($originalPassword);
                }

                // Guardar los cambios en la base de datos

                $entityManager->flush();
                return $this->redirectToRoute('user_edit', ['exito'=>"Cambios guardados con éxito",'error'=>$error??null,'id'=>$restaurante->getId()]);
            }
        }

        // Renderizar el formulario
        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
            'error' => $error ?? null,
            'exito' => $exito ?? null,
        ]);
    }

}
