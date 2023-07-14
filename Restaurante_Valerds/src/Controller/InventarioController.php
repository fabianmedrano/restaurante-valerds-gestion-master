<?php

namespace App\Controller;

use App\Data\DataInventario;
use App\Entity\Inventario;
use App\Form\InventarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/inventario")
 * @IsGranted({"ROLE_INVENTARIO", "ROLE_ADMIN"})
 */
class InventarioController extends AbstractController
{
    /**
     * @Route("/", name="inventario_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
      $di = new DataInventario();
      $inventario = $di->obtenerListadoInventario($this->getDoctrine()->getManager());

      if (!$inventario) {
        $this->addFlash(
          'alertaError',
          'Ha ocurrido un error a la hora de cargar el inventario'
        );
      }
      return $this->render('inventario/index.html.twig', [
          'inventarios' => $inventario,
      ]);
    }

    /**
     * @Route("/new", name="inventario_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
      $inventario = new Inventario();
      $form = $this->createForm(InventarioType::class, $inventario);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $di = new DataInventario();
        $resultado = $di->registrarInventario($this->getDoctrine()->getManager(), $inventario);

        if ($resultado) {
          $this->addFlash(
            'alertaCompletado',
            'Registrado correctamente'
          );
          return $this->redirectToRoute('inventario_index');
        } else {
          $this->addFlash(
            'alertaError',
            'Error al registrar'
          );
          return $this->render('inventario/new.html.twig', [
              'inventario' => $inventario,
              'form' => $form->createView(),
          ]);
        }
      }
      return $this->render('inventario/new.html.twig', [
          'inventario' => $inventario,
          'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/actualizar_stock", name="inventario_actualizar_stock", methods={"POST"})
     */
   public function actualizarStock(Request $request): Response {

     try {
      $POST = $request->getContent();
      $POST = json_decode($POST);
      $stock = $POST->stock;
      $idArticulo = $POST->idArticulo;
      $di = new DataInventario();

      $resultado = $di->actualizarStock(
         $this->getDoctrine()->getManager(),
         $this->getDoctrine()->getRepository(Inventario::class),
         $stock,
         $idArticulo,
         $this->get('session')->get('nombre')
       );

       if ($resultado) {
         return new Response("true");
       } else {
         return (new Response())->setStatusCode(500);
       }
     } catch(\Exception $e) {
        return (new Response())->setStatusCode(500);
     }
    }

    /**
     * @Route("/{idArticulo}", name="inventario_show", methods={"GET"})
     */
    public function show(Inventario $inventario): Response
    {
      return $this->render('inventario/show.html.twig', [
          'inventario' => $inventario,
      ]);
    }

    /**
     * @Route("/{idArticulo}/edit", name="inventario_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Inventario $inventario): Response
    {
      $form = $this->createForm(InventarioType::class, $inventario);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $di = new DataInventario();
        $resultado = $di->editarInventario($this->getDoctrine()->getManager(), $inventario);

        if ($resultado) {
          $this->addFlash(
            'alertaCompletado',
            'Actualizado correctamente'
          );
          return $this->redirectToRoute('inventario_index');
        } else {
          $this->addFlash(
            'alertaError',
            'Error al actualizar'
          );
          return $this->render('inventario/edit.html.twig', [
              'inventario' => $inventario,
              'form' => $form->createView(),
          ]);
        }
      }
      return $this->render('inventario/edit.html.twig', [
          'inventario' => $inventario,
          'form' => $form->createView(),
      ]);
    }
    /**
     * @Route("/{idArticulo}", name="inventario_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Inventario $inventario): Response
    {
      if ($this->isCsrfTokenValid('delete'.$inventario->getIdArticulo(), $request->request->get('_token'))) {
        $di = new DataInventario();
        $resultado = $di->eliminarProducto($this->getDoctrine()->getManager(), $inventario);

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
      } else {
         $this->addFlash(
           'alertaError',
           'Error al eliminar'
         );
       }
      return $this->redirectToRoute('inventario_index');
    }
}
