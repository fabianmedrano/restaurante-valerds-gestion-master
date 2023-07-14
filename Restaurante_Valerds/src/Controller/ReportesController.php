<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/reportes")
 * @IsGranted("ROLE_ADMIN")
 */
class ReportesController extends AbstractController
{

 /**
   * @Route("/repoInventario" ,name="repoInventario", methods={"GET","POST"})
   */
  public function index(Request $request)
  {

     $movInventario = array();
     $sql = "call sp_get_mov_inventario(:fechaInicio,:fechaFinal,:nombre);";
     $entityManager = $this->getDoctrine()->getManager();
     $stmt = $entityManager->getConnection()->prepare($sql);
     $inicio= $request->get('inicio');
     $fin= $request->get('fin');
     $nombre= $request->get('nombre');
 
     $stmt->bindParam(':fechaInicio', $inicio);
     $stmt->bindParam(':fechaFinal', $fin);
     $stmt->bindParam(':nombre', $nombre);
     $stmt->execute();
     $result = $stmt->fetchAll();

     foreach ($result as $movInventarios) {
        $movInventario[] =
              array(
              'fecha' => $movInventarios['fecha'],
              'nombreUsuario' => $movInventarios['nombre_usuario'],
              'producto' => $movInventarios['producto'],
              'stockAnterior' => $movInventarios['stock_anterior'],
              'stockActual' => $movInventarios['stock_actual']

         );
     }
     ///////////////////////////////////////////////////////////////////////////////
      // Configure Dompdf according to your needs
      $pdfOptions = new Options();
      $pdfOptions->set('defaultFont', 'Arial');
      $pdfOptions->set('isJavascriptEnabled',true);
      $date=getdate();
      $fecha= $date["mday"]."/".$date["mon"]."/".$date["year"];

      // Instantiate Dompdf with our options
      $dompdf = new Dompdf($pdfOptions);
      $dompdf = new Dompdf(array('enable_remote' => true
    ));
      // Retrieve the HTML generated in our twig file
      $html =$this->renderView('reportes/pdf.html.twig', [
        'movInventarios' => $movInventario,
        'fecha' =>  $fecha,

    ]);

      // Load HTML to Dompdf
      $dompdf->loadHtml($html);

      // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
      $dompdf->setPaper('A2', 'portrait');


      // Render the HTML as PDF
      $dompdf->render();

      // Output the generated PDF to Browser (inline view)
      $dompdf->stream("mypdf.pdf", [
          "Attachment" => false
      ]);
  }



 /**
   * @Route("/reportePlatillos" ,name="reportePlatillos")
   */
  public function indexPlatillo(Request $request)
  {

     $movPlatillos = array();
     $sql = "call sp_get_inventario_producto_masVendido(:nombre);";
     $entityManager = $this->getDoctrine()->getManager();
     $stmt = $entityManager->getConnection()->prepare($sql);
     $nombre= $request->get('nombre');
     $stmt->bindParam(':nombre', $nombre);
     $stmt->execute();
     $result = $stmt->fetchAll();

     foreach ($result as $movInventarios) {
        $movPlatillos[] =
              array(
              'nombre' => $movInventarios['nombre'],
              'descripcion' => $movInventarios['descripcion'],
              'CantidadVentas' => $movInventarios['CantidadVentas'],
              'Montototal' => $movInventarios['Montototal']
            

         );
     }
     //////////////////////////////////////////////////////////////////////////////////////
      // Configure Dompdf according to your needs
      $pdfOptions = new Options();
      $pdfOptions->set('defaultFont', 'Arial');
      $pdfOptions->set('isJavascriptEnabled',true);
      $date=getdate();
      $fecha= $date["mday"]."/".$date["mon"]."/".$date["year"];

      // Instantiate Dompdf with our options
      $dompdf = new Dompdf($pdfOptions);
      $dompdf = new Dompdf(array('enable_remote' => true
    ));
      // Retrieve the HTML generated in our twig file
      $html =$this->renderView('reportes/prodVendido.html.twig', [
        'movPlatillos' => $movPlatillos,
        'fecha' =>  $fecha,

    ]);

      // Load HTML to Dompdf
      $dompdf->loadHtml($html);

      // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
      $dompdf->setPaper('A2', 'portrait');


      // Render the HTML as PDF
      $dompdf->render();

      // Output the generated PDF to Browser (inline view)
      $dompdf->stream("mypdf.pdf", [
          "Attachment" => false
      ]);
  }



  /**
   * @Route("/reporteProductos" ,name="reporteProductos")
   */
  public function indexProducto(Request $request)
  {

     $movProductos = array();
     $sql = "call sp_get_producto_salidas_inventario(:nombre);";
     $entityManager = $this->getDoctrine()->getManager();
     $stmt = $entityManager->getConnection()->prepare($sql);
     $nombre= $request->get('nombre');
     $stmt->bindParam(':nombre', $nombre);
     $stmt->execute();
     $result = $stmt->fetchAll();
     foreach ($result as $movInventarios) {
        $movProductos[] =
              array(
              'producto' => $movInventarios['producto'],
              'CantidadSalidas' => $movInventarios['CantidadSalidas']
            

         );
     }
     //////////////////////////////////////////////////////////////////////////////////////
      // Configure Dompdf according to your needs
      $pdfOptions = new Options();
      $pdfOptions->set('defaultFont', 'Arial');
      $pdfOptions->set('isJavascriptEnabled',true);
      $date=getdate();
      $fecha= $date["mday"]."/".$date["mon"]."/".$date["year"];

      // Instantiate Dompdf with our options
      $dompdf = new Dompdf($pdfOptions);
      $dompdf = new Dompdf(array('enable_remote' => true
    ));
      // Retrieve the HTML generated in our twig file
      $html =$this->renderView('reportes/movProducto.html.twig', [
        'movProductos' => $movProductos,
        'fecha' =>  $fecha,

    ]);

      // Load HTML to Dompdf
      $dompdf->loadHtml($html);

      // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
      $dompdf->setPaper('A2', 'portrait');


      // Render the HTML as PDF
      $dompdf->render();

      // Output the generated PDF to Browser (inline view)
      $dompdf->stream("mypdf.pdf", [
          "Attachment" => false
      ]);
  }

}
