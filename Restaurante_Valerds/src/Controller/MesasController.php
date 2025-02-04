<?php

namespace App\Controller;

use App\Data\DataMesa;
use App\Entity\Mesa;
use App\Form\MesaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Psr\Log\LoggerInterface;
/**
 * @Route("/mesas")
 * @IsGranted("ROLE_ADMIN")
 */
class MesasController extends AbstractController
{

  private $logger;




  public function __construct(LoggerInterface $logger)  {
    $this->logger = $logger;
  }
  /**
   * @Route("/obtener_mesas", name="obtener_mesas", methods={"GET"})
   */
  public function obtenerMesas(): Response
  {

    try{
      $dm = new DataMesa();
      $numero =  $dm->obtenerNumeroMesa($this->getDoctrine()->getManager());
      $this->logger->info('Este es un mensaje de log informativo.  '. empty( $numero));

      if (! is_null(  $numero)) {
        return new Response($numero);
      } else {
        return (new Response())->setStatusCode(500);
      }
     } catch(\Exception $e) {
        return (new Response())->setStatusCode(500);
     }
  }
   /**
   * @Route("/modificar_mesas", name="modificar_mesas", methods={"POST"})
   */
  public function modificarMesas(Request $request)
  {
    $POST = $request->getContent();
    $POST = json_decode($POST);
    try{
      $dm = new DataMesa();
      $numero =  $dm->ModificarNumMesa($this->getDoctrine()->getManager(), $POST->numero);

      if ($numero) {
        return new Response("true");
      } else {
        return (new Response())->setStatusCode(500);
      }
    } catch(\Exception $e) {
      return (new Response())->setStatusCode(500);
    }
  }
}
