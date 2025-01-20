<?php

namespace App\Controller;

use App\Data\DataUsuarios;
use App\Data\DataMesero;
use App\Data\DataCategorias;
use App\Data\DataMesa;
use App\Entity\Usuarios;
use App\Entity\Mesa;
use App\CustomForm\PedidoTemporalType;
use App\CustomEntity\PedidoTemporal;
use App\CustomEntity\PlatilloTemporal;
use App\Form\UsuariosType;
use App\Form\UsuariosDosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/mesero")
 * @IsGranted("ROLE_MESERO")
 */
class MeseroController extends AbstractController
{
    /**
     * @Route("/", name="mesero_index", methods={"GET"})
     */
  public function index(): Response
  {
    try {
      $menu = array();
      $sql = "SELECT menu.* , categorias.nombre as nombre_categoria FROM `menu` join categorias ON categorias.id_categoria = menu.id_categoria;";
  
      $entityManager = $this->getDoctrine()->getManager();
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $platillo) {
         $menu[] =
               array(
              'idMenu' => $platillo['id_menu'],
              'nombre_menu' => $platillo['nombre'],
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

      $repository = $this->getDoctrine()->getRepository(Mesa::class);
      $dm = new DataMesa();
      $mesas = $dm->obtenerMesas($repository);

      return $this->render('mesero/menu_pedido.html.twig', [
        'menu' => $menu,
        'categorias' => $categorias,
        'mesas' => $mesas
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
     * @Route("/crear_pedido/", name="mesero_crear_pedido", methods={"POST"})
   */
  public function crearPedido(Request $request): Response {

    try {
      $POST = $request->getContent();
      $POST = json_decode($POST);
      $dm = new DataMesero();
      $dm->registrarPedido(
      $this->getDoctrine()->getManager(),
      $POST->pedido,
      $POST->mesa,
      $this->get('security.token_storage')->getToken()->getUser()->getIdUsuario()
    );
      return new Response("true");
    } catch(\Exception $e) {
      return (new Response())->setStatusCode(500);
    }
  }
}
