<?php

namespace App\Data;

use App\Entity\Inventario;
use App\Form\InventarioType;

class DataInventario {

  public function obtenerListadoInventario($entityManager) {

    try {
      $inventarios = array();
      $sql = "SELECT * FROM inventario";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      if ($result) {
        foreach ($result as $inventario) {
         $inventarios[] =
         array(
          'idArticulo' => $inventario['id_articulo'],
          'nombre' => $inventario['nombre'],
          'descripcion' => $inventario['descripcion'],
          'stock' => $inventario['stock'],

        );
       }
       $stmt->closeCursor();
       return $inventarios;
     }else{
       return null;
     }
   } catch (\Exception $e) {
    return null;
  }
}

public function registrarInventario($entityManager, $inventario) {

  try {
    $nombre = $inventario->getNombre();
    $descripcion= $inventario->getDescripcion();
    $stock = $inventario->getStock();

    $sql = "INSERT INTO inventario ( nombre, descripcion, stock) VALUES (:nombre,:descripcion,:stock) ";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':stock', $stock);
    $stmt->execute();
    if ($stmt) {
     return true;
   } else {
    return false;
  }
} catch(\Exception $e) {
  return false;
}
}

  public function editarInventario($entityManager, $inventario) {

    try {
      $idInventario = $inventario->getIdArticulo();
      $nombre = $inventario->getNombre();
      $descripcion = $inventario->getDescripcion();
      $stock = $inventario->getStock();

      $sql = "
      UPDATE inventario SET nombre = :nombre, descripcion = :descripcion, stock = :stock WHERE inventario.id_articulo = :id_articulo";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':id_articulo',$idInventario);
      $stmt->bindParam(':nombre', $nombre);
      $stmt->bindParam(':descripcion', $descripcion);
      $stmt->bindParam(':stock', $stock);
      $stmt->execute();
      if ($stmt) {
       return true;
      } else {
       return false;
      }
    } catch(\Exception $e) {
        return false;
    }
  }

  public function actualizarStock($entityManager, $repositorio, $stock, $idArticulo, $nombre) {

    try {
      $producto = $repositorio->find($idArticulo);

      if ($producto) {
        $producto->setStock($stock);
        $entityManager->flush();
        $prod=$producto->getNombre();
        $sql = "call sp_insert_mov_inventario(:nombre_usuario,:producto,:stock_actual);";
        $stmt = $entityManager->getConnection()->prepare($sql);
        $stmt->bindParam(':nombre_usuario',$nombre);
        $stmt->bindParam(':stock_actual', $stock);
        $stmt->bindParam(':producto',$prod);
        $stmt->execute();
        return true;
      } else {
        return false;
      }
    } catch(\Exception $e) {
      return false;
    }
  }

  public function eliminarProducto($entityManager, $inventario) {

    try {
      $entityManager->remove($inventario);
      $entityManager->flush();
      return true;
    } catch(\Exception $e) {
      return false;
    }
  }
}
