<?php

namespace App\Controller;

use App\Data\DataUsuarios;
use App\Data\DataPermisos;
use App\Entity\Usuarios;
use App\Form\UsuariosType;
use App\Form\UsuariosDosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/principal")
 * @IsGranted("ROLE_USUARIO")
 */
class PrincipalController extends AbstractController
{
  /**
   * @Route("/", name="principal_index", methods={"GET"})
   */
public function index(Request $request): Response
  {
    return $this->render('principal.html.twig');
  }
}
