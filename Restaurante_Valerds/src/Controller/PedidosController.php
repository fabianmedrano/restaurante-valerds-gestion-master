<?php

namespace App\Controller;

use App\Data\DataPedidos;
use App\Data\DataCategorias;
use App\Data\DataMesa;

use App\Entity\Usuarios;
use App\Entity\Pedidos;
use App\Entity\Categorias;
use App\Entity\MenuPedidos;
use App\Entity\Menu;
use App\Entity\Mesa;

use App\Form\PedidosType;
use App\Form\MenuPedidosType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/pedido")
 * @IsGranted("ROLE_CAJERO")
 */
class PedidosController extends AbstractController
{

  /**
   * @Route("/facturacion", name="facturacion")
   */
  public function facturacion(Request $request): Response
  {
    try{
      print_r($request->get('pedi'));
      $dp = new DataPedidos();
      $mesa = $dp->obtenerMesaidPedido($this->getDoctrine()->getManager(),$request->get('pedi')); 
    
      $resultado =  $dp->facturar(
        $this->getDoctrine()->getManager(), 
        $request->get('pedi'), 
        $this->get('security.token_storage')->getToken()->getUser()->getIdUsuario(),
        $request->get('nombre')/*$nombreCliente*/
      );
  
      return new Response("true");
    } catch(\Exception $e) {
      return (new Response())->setStatusCode(500);
    }
  }

  


    /**
     * @Route("/{pedido}/addorder/", name="pedidos_addorder", methods={"GET"})
     */
    public function addorder(Request $request,  $pedido): Response
    {
      try {
        $menu = array();
        $sql = "SELECT menu.*  categorias.nombre as nombre_categoria FROM `menu` join categorias ON categorias.id_categoria = menu.id_categoria;";
  
        $entityManager = $this->getDoctrine()->getManager();
        $stmt = $entityManager->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $platillo) {
           $menu[] =
                 array(
                'idMenu' => $platillo['id_menu'],
                'nombre_menu' => $platillo['nombre_menu'],
                'descripcion' => $platillo['descripcion'],
                'precio' => $platillo['precio'],
                'estado' => $platillo['estado'],
                'id_categoria' => $platillo['id_categoria'],
                'nombre_categoria' => $platillo['nombre_categoria']
            );
        }

        $stmt->closeCursor();
        $dc = new DataCategorias();
        $categorias = $dc->obtenerListadoCategoriasAsignadas($this->getDoctrine()->getManager());
        $dp = new DataPedidos();
        $pedidos = $dp->obtenerListadoPlatillosPedido($this->getDoctrine()->getManager(),$pedido);
        $dm = new DataMesa();
        $mesa = $dm->obtenerMesa($this->getDoctrine()->getManager(),$pedido);

        return $this->render('pedidos/addorder.html.twig', [
          'pedido'=>$pedido,
          'pedidos' => $pedidos,
          'menu' => $menu,
          'categorias' => $categorias,
          'mesa' => $mesa
        ]);
      } catch (\Exception $e) {
        $this->addFlash(
          'alertaError',
          'Ha ocurrido un error a la hora de cargar la página.'
        );
        return $this->render('principal.html.twig');
      }
    }


       /**
    * @Route("/suborder", name="suborder", methods={"GET"})
    */
    public function suborder(Request $request): Response {
     
      try {
          $dp = new DataPedidos();
          $dp->insertsubpedido(
            $this->getDoctrine()->getManager(),
            json_decode($request->get('pedido')),
            $request->get('mesa'),
            $this->get('security.token_storage')->getToken()->getUser()->getIdUsuario(),
            $request->get('idPedido')
          );
            
            return new Response("true");
          } catch(\Exception $e) {
            return (new Response())->setStatusCode(500);
          }
      }
  

     /**
     * @Route("/{mesa}/tableorders", name="pedidos_tableorders_show", methods={"GET","POST"})
     */
     public function viewTableOrders(Request $request, $mesa): Response
    {

      $dp = new DataPedidos();

      $pedidos = $dp->obtenerListadoPedidosMesa($this->getDoctrine()->getManager(),$mesa);

        return $this->render('pedidos/tableorders.html.twig', [
            'pedidos' => $pedidos,
            'mesa' =>$mesa,
        ]);
    }

    /**
     * @Route("/{mesa}/{cantidad}/viewtable", name="pedidos_showtable", methods={"GET","POST"})
     */
    public function showtable(Request $request, $mesa, $cantidad): Response
    {
        var_dump($cantidad);
        if ($cantidad == 1) {
            $dp = new DataPedidos();
            $pedido = $dp->obtenerPedidoMesa($this->getDoctrine()->getManager(), $mesa);
    
            if (empty($pedido)) {
                return $this->redirectToRoute('error_route'); 
            }
    
            return $this->redirectToRoute('pedidos_show', ['mesa' => $mesa, 'pedido' => $pedido]);
        } else {
            return $this->redirectToRoute('pedidos_tableorders_show', ['mesa' => $mesa]);
        }
    }


    /**
     * @Route("/{mesa}/{pedido}/view", name="pedidos_show", methods={"GET","POST"})
     */
    public function show(Request $request, $mesa, $pedido ): Response
    {

      if (empty($pedido)) {

        throw new \Exception("The pedido ID is empty.");
    
    }
        $dp = new DataPedidos();

        $pedidos = $dp->obtenerListadoPlatillosPedido($this->getDoctrine()->getManager(),$pedido);
        $total = $dp->obtenerTotalPedido($this->getDoctrine()->getManager(),$pedido);

        return $this->render('pedidos/show.html.twig', [
        'pedidos' => $pedidos,
        'pedido' => $pedido,
        'mesa' => $mesa,
        'total' =>   $total
        ]);

    }

    /**
     * @Route("/{idPedido}/edit", name="pedidos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pedidos $pedido): Response
    {

      $dp = new DataPedidos();
      $pedidos = $dp->obtenerListadoPlatillosPedido($this->getDoctrine()->getManager(),$pedido->getIdPedido());

        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pedidos_index', [
                'idPedido' => $pedido->getIdPedido(),
            ]);
        }

        return $this->render('pedidos/edit.html.twig', [
            'mesa' => $pedido->getNumeromesa(),
            'pedido'=> $pedido->getIdPedido(),
            'pedidos' => $pedidos,
            'form' => $form->createView(),
        ]);
    }

  /**
    * @Route("/agregarPlatillaPedido", name="agregarAPedido", methods={"GET"})
    */
    public function agregarPlatillosAPedido(Request $request): Response {

        try {
          $dm = new DataPedidos();
          $dm->agregarAPedido(
            $this->getDoctrine()->getManager(),
            json_decode($request->get('pedido')),
            $request->get('mesa'),
            $request->get('idPedido')
          );
          return new Response("true");
        } catch(\Exception $e) {
          return (new Response())->setStatusCode(500);
        }
    }


    /**
     * @Route("/{pedido}/{mesa}", name="pedidos_delete")
     */
    public function delete(Request $request, $pedido,$mesa): Response
    {
      try {
        $dm = new DataPedidos();
        $dm->eliminarPedido( $this->getDoctrine()->getManager(),$pedido);

          $this->addFlash(
          'alertaCompletado',
          'Eliminado correctamente');
          return $this->redirectToRoute('pedidos_tableorders_show',[ 'mesa' =>  $mesa]);
        } catch(\Exception $e) {
          $this->addFlash(
            'alertaError',
            'Error al eliminar');

          return $this->redirectToRoute('pedidos_show',[ 'mesa' =>  $mesa,'pedido'=>$pedido ]);
        }

    }

  /**
   * @Route("/{idPedido}/{idMenu}/deletedetalle", name="detalle_delete")
   */
    public function deleteDetalle(Request $request, $idPedido , $idMenu): Response
    {


      $dp = new DataPedidos();
      $resultado =  $dp->eliminarDetallePedido($this->getDoctrine()->getManager(),$idMenu,$idPedido);
        if ($resultado) {
          $this->addFlash(
            'alertaCompletado',
            'Eliminado correctamente'
          );

        } else {
          $this->addFlash(
            'alertaError',
            'Error al eliminar'
          );
        }

      return $this->redirectToRoute('pedidos_edit',[ 'idPedido' =>  $idPedido]);
    }



  /**
   * @Route("/{pedido1}/{pedido2}/join", name="pedidos_join_orders")
   */
  public function joinOrders(Request $request, $pedido1, $pedido2 ): Response
  {

    $dp = new DataPedidos();
    $mesa = $dp->obtenerMesaidPedido($this->getDoctrine()->getManager(),$pedido1); 
    $pedidos1 = $dp->obtenerListadoPlatillosPedido($this->getDoctrine()->getManager(),$pedido1);
    $pedidos2 = $dp->obtenerListadoPlatillosPedido($this->getDoctrine()->getManager(), $pedido2 );

    return $this->render('pedidos/joinorders.html.twig', [
        'mesa' => $mesa,
        'pedido1'=> $pedido1,
        'pedido2'=> $pedido2,
        'pedidos1' => $pedidos1,
        'pedidos2' => $pedidos2,
    ]);
  }


  /**
    * @Route("/guardarunion", name="guardar_union", methods={"GET"})
    */
    public function guardarUnion(Request $request): Response {
      try{
          $dm = new DataPedidos();
          $dm->mezclarPedidos(
            $this->getDoctrine()->getManager(),
            json_decode($request->get('pedido1')),
            $request->get('idPedido1'),
            $request->get('idPedido2')
          );
          $dm->mezclarPedidos(
            $this->getDoctrine()->getManager(),
            json_decode($request->get('pedido2')),
            $request->get('idPedido2'),
            $request->get('idPedido1')
          );
          
          return new Response("true");
        } catch(\Exception $e) {
          return (new Response())->setStatusCode(500);
        }
    }





  /**
   * @Route("/unirtodo", name="unirtodo")
   */
  public function unirTodo(Request $request): Response
  {
    
    $dp = new DataPedidos();
    $mesa = $dp->obtenerMesaidPedido(
      $this->getDoctrine()->getManager(),
      json_decode($request->get('pedido'))[0]
    ); 


    $resultado =  $dp->unirTodo($this->getDoctrine()->getManager(), json_decode($request->get('pedido'))); /* TRABAJANDO CON ESTE */


    if ($resultado) {
      $this->addFlash(
        'alertaCompletado',
        'Unido correctamente'
      );

    } else {
      $this->addFlash(
        'alertaError',
        'Error al unir'
      );
    }

      return $this->redirectToRoute('pedidos_tableorders_show',[ 'mesa' =>  $mesa]);
  }



}
