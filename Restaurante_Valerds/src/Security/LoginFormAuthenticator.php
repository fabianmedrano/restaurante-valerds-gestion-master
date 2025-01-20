<?php
// src/Security/LoginFormAuthenticator.php
namespace App\Security;

use App\Data\DataPermisos;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'usuario' => $request->request->get('usuario'),
            'contrasena' => $request->request->get('contrasena'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['usuario']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        $user = $this->entityManager->getRepository(Usuarios::class)->findOneBy(['usuario' => $credentials['usuario'], 'contrasena' => $credentials['contrasena'], 'estado' => 1]);
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
          throw new CustomUserMessageAuthenticationException('Usuario could not be found.');
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['contrasena']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $usuario = $token->getUser();
        $session = $request->getSession();
        $session->set("nombre", $usuario->getNombre());

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        // For example : return new RedirectResponse($this->router->generate('some_route'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->router->generate('principal'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }
}
