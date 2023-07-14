<?php

namespace App\Controller;

use App\Data\DataUsuarios;
use App\Data\DataPermisos;
use App\Entity\Usuarios;
use App\Form\UsuariosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/usuarios")
 * @IsGranted("ROLE_ADMIN")
 */
class UsuariosController extends AbstractController
{
    /**
     * @Route("/", name="usuarios_index", methods={"GET"})
     */
  public function index(Request $request): Response
    {
      $du = new DataUsuarios();
      $usuarios = $du->obtenerListadoUsuarios($this->getDoctrine()->getManager());

      if (!$usuarios) {
        $this->addFlash(
          'alertaError',
          'Ha ocurrido un error a la hora de cargar los usuarios'
        );
      }
      return $this->render('usuarios/index.html.twig', [
          'usuarios' => $usuarios,
      ]);
    }
    /**
     * @Route("/new", name="usuarios_new", methods={"GET","POST"})
     */
   public function new(Request $request, \Swift_Mailer $mailer): Response
    {
      $dp = new DataPermisos();
      $permisos = $dp->obtenerPermisos($this->getDoctrine()->getManager());

      if ($permisos) {
        $usuario = new Usuarios();
        $usuario->setPermisosUsuario($permisos);
        $form = $this->createForm(UsuariosType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $du = new DataUsuarios();
          $resultado = $du->registrarUsuario($this->getDoctrine()->getManager(), $usuario);

          if ($resultado == true) {

            try {
              $message = (new \Swift_Message('Datos de cuenta'))
                ->setFrom('alvaromontero45@gmail.com')
                ->setTo($usuario->getCorreo())
                ->setBody(
                    $this->renderView(
                        'correo_cuenta.html.twig', [
                          'usuario' => $usuario->getUsuario(),
                          'contrasena' => $usuario->getContrasena()
                        ]
                    ),
                    'text/html'
                );
              //Para evitar enviar correos por accidente
              //$mailer->send($message);
            } catch (\Exception $e) {
            }
            $this->addFlash(
              'alertaCompletado',
              'Registrado correctamente'
            );
            return $this->redirectToRoute('usuarios_index');
          } else {
            $this->addFlash(
              'alertaError',
              'Error al registrar'
            );
            return $this->render('usuarios/new.html.twig', [
                'usuario' => $usuario,
                'form' => $form->createView(),
            ]);
          }
        }
      } else {
        $this->addFlash(
          'alertaError',
          'Error al cargar la página'
        );
        return $this->redirectToRoute('usuarios_index');
      }
      return $this->render('usuarios/new.html.twig', [
          'usuario' => $usuario,
          'form' => $form->createView(),
      ]);
    }
    /**
     * @Route("/{idUsuario}", name="usuarios_show", methods={"GET"})
     */
    public function show(Usuarios $usuario): Response
    {
      return $this->render('usuarios/show.html.twig', [
          'usuario' => $usuarios,
      ]);
    }

    /**
     * @Route("/{idUsuario}/edit", name="usuarios_edit", methods={"GET","POST"})
     */
 public function edit(Request $request, Usuarios $usuario, \Swift_Mailer $mailer): Response
    {
      $dp = new DataPermisos();
      $permisos = $dp->obtenerPermisosUsuario($usuario->getIdUsuario(), $this->getDoctrine()->getManager());

      if ($permisos) {
        $valAdmin = false;
        $conteo = 0;
        foreach($permisos as $permiso) {

          if ($permiso->getNombre() == "Administrador") {

            if ($permiso->getEstado() == true) {
                $valAdmin = true;
            } else {
                //Quitar de los visibles para usuarios
                unset($permisos[$conteo]);
            }
            break;
          }
          $conteo++;
        }
        if ($valAdmin == false) {
          $usuario->setPermisosUsuario($permisos);
        }
        $form = $this->createForm(UsuariosType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          if ($valAdmin == true) {
              $usuario->setEstado(true);
          }

          $du = new DataUsuarios();
          $resultado = $du->editarUsuario($this->getDoctrine()->getManager(), $usuario);

          if ($resultado) {
            $this->addFlash(
              'alertaCompletado',
              'Actualizado correctamente'
            );
            return $this->redirectToRoute('usuarios_index');
          } else {
            $this->addFlash(
              'alertaError',
              'Error al actualizar'
            );
            return $this->render('usuarios/edit.html.twig', [
                'usuarios' => $usuario,
                'admin' => $valAdmin,
                'form' => $form->createView(),
            ]);
          }
        }
      } else {
        $this->addFlash(
          'alertaError',
          'Error al obtener el usuario'
        );
        return $this->redirectToRoute('usuarios_index');
      }
      return $this->render('usuarios/edit.html.twig', [
          'usuario' => $usuario,
          'admin' => $valAdmin,
          'form' => $form->createView()
      ]);
    }
    /**
     * @Route("/{idUsuario}", name="usuarios_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Usuarios $usuario): Response
    {
      if ($this->isCsrfTokenValid('delete'.$usuario->getIdUsuario(), $request->request->get('_token'))) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($usuario);
          $entityManager->flush();
      }
      return $this->redirectToRoute('usuarios_index');
    }
}
