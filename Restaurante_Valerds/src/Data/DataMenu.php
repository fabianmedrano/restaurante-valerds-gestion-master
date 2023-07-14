<?php

namespace App\Data;

use App\Entity\Menu;
use App\Form\MenuType;

class DataMenu {

  public function obtenerListadoMenu($entityManager) {

    try {
      $menu = array();
      $sql = "call sp_get_menu_completo();";
       $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      
      if ($result) {
        foreach ($result as $platillo) {
         $menu[] =
         array(
          'idMenu' => $platillo['id_menu'],
          'nombreMenu' => $platillo['nombre_menu'],
          'descripcion' => $platillo['descripcion'],
          'precio' => $platillo['precio'],
          'estado' => $platillo['estado'],
          'idCategoria' => $platillo['id_categoria'],
          'nombreCategoria' => $platillo['nombre_categoria']

       );
       }
       $stmt->closeCursor();
       return $menu;
     }else{
       return null;
     }
   } catch (\Exception $e) {
    return null;
  }
}
}
