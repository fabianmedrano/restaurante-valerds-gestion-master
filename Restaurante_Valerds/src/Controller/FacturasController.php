<?php

namespace App\Controller;

use App\Data\DataFacturas;
use App\Entity\Facturas;
use App\Form\FacturasType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/facturas")
 * @IsGranted("ROLE_ADMIN")
 */
class FacturasController extends AbstractController
{ 
    /**
     * @Route("/", name="facturas_index", methods={"GET"})
     */
   public function index(Request $request): Response
    {

      $dc = new DataFacturas();
      $facturas = $dc->obtenerListadoFacturas($this->getDoctrine()->getManager());

    if (!$facturas) {
       $arrayTotalFactura[1]=0;

      $this->addFlash(
        'alertaError',
        'Ha ocurrido un error a la hora de cargar las facturas'

      );
    }
       if ($facturas!=null) { 
        $arrayFactura = array();
        foreach ($facturas as $factura) {
         $arrayFactura[]=
         array(
          'id_factura' => $factura['id_factura']
        );
       }

       foreach ($arrayFactura as $arrayF) {
        $totalFacturas = $dc->obtenerDetalleFacturaTotal($this->getDoctrine()->getManager(),
          $arrayF['id_factura']);
        $arrayTotalFactura[] = $totalFacturas;
      }
    }

    return $this->render('facturas/index.html.twig', [
      'facturas' => $facturas,
      'arrayTotalFactura' =>$arrayTotalFactura,
    ]);
  }


    /**
     * @Route("/new", name="facturas_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
      $factura = new Facturas();
      $form = $this->createForm(FacturasType::class, $factura);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($factura);
        $entityManager->flush();

        return $this->redirectToRoute('facturas_index');
      }

      return $this->render('facturas/new.html.twig', [
        'factura' => $factura,
        'form' => $form->createView(),
      ]);
    }



    /**
     * @Route("/{idFactura}/edit", name="facturas_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Facturas $factura): Response
    {
      $form = $this->createForm(FacturasType::class, $factura);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('facturas_index', [
          'idFactura' => $factura->getIdFactura(),
        ]);
      }

      return $this->render('facturas/edit.html.twig', [
        'factura' => $factura,
        'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/{idFactura}", name="facturas_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Facturas $factura): Response
    {
      if ($this->isCsrfTokenValid('delete'.$factura->getIdFactura(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($factura);
        $entityManager->flush();
      }

      return $this->redirectToRoute('facturas_index');
    }


  /**
    * @Route("/detalleFactura", name="factura_detalle", methods={"GET","POST"})
     */

  public function detalleFactura(Request $request): Response
  {

     try {
    $df = new DataFacturas();
    $factura = $df->obtenerDetalleFactura($this->getDoctrine()->getManager(),$request->get('factura'));
    $totalFacturas = $df->obtenerDetalleFacturaTotal($this->getDoctrine()->getManager(), $request->get('factura'));


     $facturas[0]=$factura;
     $facturas[1]=$totalFacturas;

       if ($facturas) {
         return new Response(json_encode($facturas), 200);
       } else {
         return (new Response())->setStatusCode(500);
       }
     } catch(\Exception $e) {
        return (new Response())->setStatusCode(500);
     }
    }


  /**
    * @Route("/{idFactura}/detalleTotal", name="factura_total", methods={"GET","POST"})
     */
  public function detalleTotal(Request $request,$idFactura): Response
  {
    $facturas = array();
    $sql = "call sp_get_total_factura('.$idFactura.');";
    $entityManager = $this->getDoctrine()->getManager();
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $factura) {
     $facturas[] =
     array(
      'total'=> $factura['total'],
    );

   }

   return $this->render('facturas/detalleFactura.html.twig', [
    'facturas' => $facturas,
  ]);
 }





}
