<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SeguridadController extends AbstractController
{
  /**
   * @Route("/", name="app_login")
   */
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      return $this->redirectToRoute('principal');
    }

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $ultimoUsuario = $authenticationUtils->getLastUsername();

    if ($error) {
      $this->addFlash(
        'alertaError',
        'Inicio de sesión invalido'
      );
    }
    return $this->render('/security/inicioSesion.html.twig', ['ultimo_usuario' => $ultimoUsuario, 'error' => $error]);
  }
}
