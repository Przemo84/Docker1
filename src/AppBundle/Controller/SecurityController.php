<?php

namespace AppBundle\Controller;


use AppBundle\Form\LoginForm;
use AppBundle\Repository\MyUserRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class SecurityController extends Controller
{

    public function loginAction(Request $request)
    {
        $userRepository = $this->get('app.repo.users');
        // Jeżeli formularz jest pusty
        if (!(isset($_POST['_username']))) {
            return $this->render('articles/login.html.twig', array(
//            'last_username' => $lastUsername,
//            'error'         => $error,
            ));
            // Jeżeli użytkownika nie ma w bazie
        } elseif ($userRepository->loadUserByUsername($_POST['_username']) == null) {
            return new Response('Podany użytkownik nie istnieje');

            // Jeżeli użytkownik jest w bazie to sprawdz jego haslo
        } elseif ($userRepository->loadUserByUsername($_POST['_username'])->getUsername() == $_POST['_username']) {

            //Jeżeli podane hasło jest niezgodne
            if (!password_verify($_POST['_password'], $userRepository->loadUserByUsername($_POST['_username'])->getPassword())) {
                return new Response("Bad credentials");

                //Jeżeli podane hasło jest OK.
            } else {
                return $this->redirectToRoute('article_index');
            }


        }

    }

//        // get the login error if there is one
//        $error = $authUtils->getLastAuthenticationError();
//
//        // last username entered by the user
//        $lastUsername = $authUtils->getLastUsername();


}