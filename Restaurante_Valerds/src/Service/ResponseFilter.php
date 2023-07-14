<?php
namespace App\Service;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Usuarios;
use App\Data\DataPermisos;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Security\Core\Security;


use App\Controller\InventarioController;
use App\Controller\UsuariosController;

use \Symfony\Component\HttpKernel\Event\GetResponseEvent;
use \Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ResponseException;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;



class ResponseFilter implements EventSubscriberInterface
{
  private $entityManager;
  private $tokenStorage;
  private $session;
  private $security;

  public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, SessionInterface $session, Security $security)
  {
    $this->entityManager = $entityManager;
    $this->tokenStorage = $tokenStorage;
    $this->session = $session;
    $this->security = $security;
  }

  public function onKernelController(FilterControllerEvent $event)
  {
    /*$controller = $event->getController();

    if (!is_array($controller)) {
        return;
    }

    if ($controller[0] instanceof PrincipalController) {

      if (!$this->security->isGranted('ROLE_USUARIO')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'logout'));
        throw $ex;
      }
    }

    if ($controller[0] instanceof MeseroController) {

      if (!$this->security->isGranted('ROLE_MESERO')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }

    if ($controller[0] instanceof PedidosController) {

      if (!$this->security->isGranted('ROLE_CAJERO')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }

    if ($controller[0] instanceof CategoriasController) {

      if (!$this->security->isGranted('ROLE_ADMIN')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }

    if ($controller[0] instanceof UsuariosController) {

      if (!$this->security->isGranted('ROLE_ADMIN')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }

    if ($controller[0] instanceof MenuController) {

      if (!$this->security->isGranted('ROLE_ADMIN')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }

    if ($controller[0] instanceof FacturasController) {

      if (!$this->security->isGranted('ROLE_ADMIN')) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }


    if ($controller[0] instanceof InventarioController) {

      if ((!$this->security->isGranted('ROLE_INVENTARIO')) && (!$this->security->isGranted('ROLE_ADMIN'))) {
        $ex = new AccessDeniedHttpException();
        $ex->setHeaders(array('tipo' => 'principal'));
        throw $ex;
      }
    }*/
  }

  public function onKernelRequest(GetResponseEvent $event) {
    $tokenUser = null;
    if ($this->tokenStorage->getToken() != null) {
        $tokenUser = $this->tokenStorage->getToken()->getUser();
    }

    if ($tokenUser instanceof Usuarios) {
      $user = $this->entityManager->getRepository(Usuarios::class)->findOneBy(['usuario' => $tokenUser->getUsuario(), 'contrasena' => $tokenUser->getContrasena(), 'estado' => 1]);

      if ($user) {
        $dp = new DataPermisos();
        $permisos = $dp->obtenerPermisosUsuario($user->getIdUsuario(), $this->entityManager);
        $permisosUsuario = ['ROLE_USUARIO'];

        foreach ($permisos as $permiso) {

          if ($permiso->getEstado() == true) {
            switch ($permiso->getIdPermiso()) {
            //Mesero
            case 1:
                array_push($permisosUsuario, 'ROLE_MESERO');
                break;
            //Cajero
            case 2:
                array_push($permisosUsuario, 'ROLE_CAJERO');
                break;
            //Administrador
            case 3:
                array_push($permisosUsuario, 'ROLE_ADMIN');
                break;
            //Inventario
            case 4:
                array_push($permisosUsuario, 'ROLE_INVENTARIO');
                break;
            }
          }
        }
        $user->setRoles($permisosUsuario);
      } else {
        $user = new Usuarios();
        $user->setIdUsuario(0);;
        $user->setRoles(array());
      }
      $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
      $this->tokenStorage->setToken($token);
      $this->session->set('_security_main', serialize($token));
      $this->session->set("nombre", $user->getNombre());
    }
  }

  public function onKernelException(GetResponseForExceptionEvent $event) {
    /*
    $exception = $event->getException();

    if ($exception instanceof AccessDeniedHttpException) {
        $response = new RedirectResponse("/principal");
        $event->setResponse($response);
      $exHeaders = $exception->getHeaders();

      if ($exHeaders["tipo"]) {

        if ($exHeaders["tipo"] == "principal") {
          $response = new RedirectResponse("/principal");
        } else {
          $response = new RedirectResponse("/logout");
        }
      } else {
        $response = new RedirectResponse("/logout");
      }
      $event->setResponse($response);
    }
    */
  }

  public function onKernelResponse(FilterResponseEvent $event)
  {
    $response = $event->getResponse();
  //  $response->headers->addCacheControlDirective('no-cache', true);
    $response->headers->addCacheControlDirective('max-age', 0);
    $response->headers->addCacheControlDirective('must-revalidate', true);
    $response->headers->addCacheControlDirective('no-store', true);
  }

  public static function getSubscribedEvents()
  {
   return [
       KernelEvents::CONTROLLER => 'onKernelController',
       KernelEvents::REQUEST => 'onKernelRequest',
       KernelEvents::RESPONSE => 'onKernelResponse',
   ];
  }
}
