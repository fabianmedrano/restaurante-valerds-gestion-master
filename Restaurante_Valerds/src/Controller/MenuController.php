<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Menu;
use App\Entity\Categorias;
use App\Form\MenuType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

 /**
   * @Route("/menu")
   * @IsGranted("ROLE_ADMIN")
   */
class MenuController extends AbstractController
{
  /**
   * @Route("/{idMenu}/edit", name="menu_edit", methods={"GET","POST"})
   */
 public function edit(Request $request, Menu $menu): Response
  {
      $categorias = $this->getDoctrine()
      ->getRepository(Categorias::class)
      ->findAll();
      /******/
        $lista = array();
      foreach($categorias as $t) {
          $lista[ $t->getNombre()] = $t->getIdCategoria() ;
      }

      $options = array('categorias' => $lista );
      /******/
      $form = $this->createForm(MenuType::class,$menu,$options);
      $form->handleRequest($request);
      /******/



      $entityManager = $this->getDoctrine()->getManager();
      $menu = $entityManager->getRepository(Menu::class)->find($menu->getIdMenu());


      if ($form->isSubmitted() && $form->isValid()) {


          $nombre = $form->get('nombre')->getData();
          $descipcion =$form->get('descripcion')->getData();
          $precio =$form->get('precio')->getData();
          $categoria = $form->get('idCategoria')->getData();
          $estado = $form->get('estado')->getData();

          $menu->setNombre($nombre);
          $menu->setDescripcion($descipcion);
          $menu->setEstado($estado);
          $menu->setPrecio ( $precio);
          $menu->setIdCategoria( $categoria);

          $entityManager->flush();

          try {
              $this->addFlash(
                    'alertaCompletado',
                    'Actualizado correctamente'
              );
              return $this->redirectToRoute('menu_index');
          } catch (\Exception $e) {

              echo ($e);
              $this->addFlash(
                'alertaError',
                'Error al actualizar'
              );

              return $this->render('menu/edit.html.twig', [
                  'menu' => $menu,
                  'form' => $form->createView(),
                  'categorias' => $categorias,
              ]);
          }

      }
      /******/
      return $this->render('menu/edit.html.twig', [
          'menu' => $menu,
          'form' => $form->createView(),
          'categorias' => $categorias,
      ]);
  }

 /**
   * @Route("/new", name="new_menu", methods={"GET","POST"}))
   */
   public function new(Request $request): Response
  {
		$categorias = $this->getDoctrine()
  	->getRepository(Categorias::class)
  	->findAll();


       $lista = array();
      foreach($categorias as $t) {
          $lista[ $t->getNombre()] = $t->getIdCategoria() ;
      }

      $options = array('categorias' => $lista );
  	/******/
		$menu = new Menu();
      $form = $this->createForm(MenuType::class, $menu,$options);
      $form->handleRequest($request);
      $entityManager = $this->getDoctrine()->getManager();


      if ($form->isSubmitted() && $form->isValid()) {

          $menu->setNombre($form->get('nombre')->getData());
          $menu->setDescripcion($form->get('descripcion')->getData());
          $menu->setEstado(true/*$form->get('estado')->getData()*/);
          $menu->setPrecio($form->get('precio')->getData());
          $menu->setIdCategoria( $form->get('idCategoria')->getData());
          //debido a que son div de boostrap no captura el la categoria
          $entityManager->persist($menu);
          $entityManager->flush();
          try {

              $this->addFlash(
                  'alertaCompletado',
                  'Registrado correctamente'
              );
              return $this->redirectToRoute('menu_index');
          } catch (\Exception $e) {
              $this->addFlash(
                  'alertaError',
                  'Error al registrar'
              );
              return $this->render('menu/new.html.twig', [
                  'form' => $form-> createView(),
                  'categorias' => $categorias,
              ]);
          }
  	}

	return $this->render('menu/new.html.twig', [
	  'form' => $form-> createView(),
	  'categorias' => $categorias,
      ]);
  }


  /**
   * @Route("/", name="menu_index", methods={"GET"})
   */

   public function index(Request $request): Response
  {
    // menu
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

    return $this->render('menu/index.html.twig', [
      'menu' => $menu
    ]);
  }
  /**
   * @Route("/{idMenu}", name="menu_show", methods={"GET"})
   */
  public function show( Menu $menu): Response
  {
    return $this->redirectToRoute('principal_index');
  }
}
