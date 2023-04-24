<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    public function register(Request $request, UserPasswordHasherInterface $encoder, ManagerRegistry $registry)
    {
        // Crear el formulario
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // Rellenar el objeto con los datos del form
        $form->handleRequest($request);

        // Comprobar si el form se ha enviado
        if($form->isSubmitted() && $form->isValid()){
            // Modificar el objeto para guardarlo
            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTime('now'));

            // Cifrando la contraseña
            $encoder = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encoder);

            // Guardar usuario
            $registry->getManager()->persist($user);
            $registry->getManager()->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function login(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUserName
        ));
    }
}
