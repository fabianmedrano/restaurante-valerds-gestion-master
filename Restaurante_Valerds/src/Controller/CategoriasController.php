<?php

namespace App\Controller;

use App\Data\DataCategorias;
use App\Entity\Categorias;
use App\Form\CategoriasType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/categorias")
 */
class CategoriasController extends AbstractController
{
    /**
     * @Route("/", name="categorias_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
      $dc = new DataCategorias();
      $categorias = $dc->obtenerListadoCategorias($this->getDoctrine()->getManager());

      if (!$categorias) {
        $this->addFlash(
          'alertaError',
          'Ha ocurrido un error a la hora de cargar las categorías'
        );
      }
      return $this->render('categorias/index.html.twig', [
        'categorias' => $categorias,
      ]);
    }
    /**
     * @Route("/new", name="categorias_new_nombre", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
      try{

        $dc = new DataCategorias();
        $todasCategorias = $dc->obtenerListadoCategorias($this->getDoctrine()->getManager());
        $res=true;


        foreach ($todasCategorias  as $categoria) {
          if($categoria['nombre']===$request->get('nombre')){
            $res=false;
          }
        }
        $resultado=false;
        if($res){
          $resultado = $dc->registrarCategoria(
           $this->getDoctrine()->getManager(),
           $this->getDoctrine()->getRepository(Categorias::class),
           $request->get('nombre')
         );
        }
        $todasCategorias = $dc->obtenerListadoCategorias($this->getDoctrine()->getManager());

        if($resultado){
         return new Response(json_encode($todasCategorias), 200);
       } else {
         return new Response("false");
       }
     } catch(\Exception $e) {
      return (new Response())->setStatusCode(500);
    }
  }

  /**
   * @Route("/{idCategoria}", name="categorias_show", methods={"GET"})
   */
  //public function show(Categorias $categoria): Response
//  {
  //    return $this->render('categorias/show.html.twig', [
  //        'categoria' => $categoria,
  //    ]);
//  }

  /**
   * @Route("/{idCategoria}/edit", name="categorias_edit", methods={"GET","POST"})
   */
  public function edit(Request $request, Categorias $categoria): Response
  {
    $dc = new DataCategorias();
    $form = $this->createForm(CategoriasType::class, $categoria);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $resultado = $dc->editarCategoria($this->getDoctrine()->getManager(), $categoria);

      if ($resultado) {
        $this->addFlash(
          'alertaCompletado',
          'Actualizado correctamente'
        );
        return $this->redirectToRoute('categorias_index');
      } else {
        $this->addFlash(
          'alertaError',
          'Error al actualizar'
        );
        return $this->render('categorias/edit.html.twig', [
          'categoria' => $categoria,
          'form' => $form->createView(),
        ]);
      }
    }
    return $this->render('categorias/edit.html.twig', [
      'categoria' => $categoria,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/{idCategoria}", name="categorias_delete", methods={"DELETE"})
   */
//  public function delete(Request $request, Categorias $categoria): Response
//  {
  //    if ($this->isCsrfTokenValid('delete'.$categoria->getIdCategoria(), $request->request->get('_token'))) {
  //        $entityManager = $this->getDoctrine()->getManager();
  //        $entityManager->remove($categoria);
  //        $entityManager->flush();
  //    }

  //    return $this->redirectToRoute('categorias_index');
  //}


  public function verificar($nombre) {
    $dc = new DataCategorias();
    $todasCategorias = $dc->obtenerListadoCategorias($this->getDoctrine()->getManager());
    $res= true;
    foreach ($todasCategorias  as $categoria) {
      if($categoria['nombre']===$nombre){
       $res=false;
     }
   }
   return $res;
 }

   /**
     * @Route("/actualizar_nombre", name="categoria_actualizar_nombre", methods={"GET","POST"})
     */
   public function actualizarNombre(Request $request): Response {

     try {

       $resultado = false;
       $dc = new DataCategorias();
       $nombre=$request->get('nombre');

       //verificar($nombre);

       $todasCategorias = $dc->obtenerListadoCategorias($this->getDoctrine()->getManager());
       $res= true;
       foreach ($todasCategorias  as $categoria) {
        if($categoria['nombre']===$nombre){
         $res=false;
       }
     }

     if($res){
       $resultado = $dc->actualizarNombre(
         $this->getDoctrine()->getManager(),
         $this->getDoctrine()->getRepository(Categorias::class),
         $request->get('nombre'),
         $request->get('idCategoria')
       );  
     }
     if($resultado){
      return new Response("true");

    }else {
     return new Response("false");
   }
 } catch(\Exception $e) {
  return (new Response())->setStatusCode(500);
}
}


}
