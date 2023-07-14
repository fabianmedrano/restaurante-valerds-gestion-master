<?php
namespace App\Service;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\HttpUtils;

class LogoutSuccess implements LogoutSuccessHandlerInterface
{
  private $router;
  private $httpUtils;

  public function __construct(HttpUtils $httpUtils, RouterInterface $router)
  {
    $this->router = $router;
    $this->httpUtils = $httpUtils;
  }

  public function onLogoutSuccess(Request $request)
  {
   return new RedirectResponse($this->router->generate('app_login'));
  }
}
