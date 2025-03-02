<?php

namespace App\Data;

use App\Entity\Categorias;
use App\Form\CategoriasType;

class DataCategorias {

 public function obtenerListadoCategorias($entityManager) {

   try {
     $categorias = array();
     $sql = "SELECT * FROM categorias";
     $stmt = $entityManager->getConnection()->prepare($sql);
     $stmt->execute();
     $result = $stmt->fetchAll();

     if ($result) {
       foreach ($result as $categoria) {
        $categorias[] =
        array(
          'nombre' => $categoria['nombre'],
          'idCategoria' => $categoria['id_categoria'],
        );
      }
      $stmt->closeCursor();
      return $categorias;
    }else{
     return null;
   }
 } catch (\Exception $e) {
  return null;
}
}

public function obtenerListadoCategoriasAsignadas($entityManager) {

 try {
   $categorias = array();
   $sql = "SELECT * FROM categorias";
   $stmt = $entityManager->getConnection()->prepare($sql);
   $stmt->execute();
   $result = $stmt->fetchAll();

   if ($result) {
     foreach ($result as $categoria) {
      $categorias[] =
      array(
        'nombre' => $categoria['nombre'],
        'idCategoria' => $categoria['id_categoria'],
      );
    }
    $stmt->closeCursor();
    return $categorias;
  }else{
   return null;
 }
} catch (\Exception $e) {
  return null;
}
}

public function registrarCategoria($entityManager, $categoria,$nombre) {

 try {
  $sql = "INSERT INTO categorias ( nombre) VALUES (:nombre) ";
  $stmt = $entityManager->getConnection()->prepare($sql);
  $stmt->bindParam(':nombre', $nombre);
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

public function editarCategoria($entityManager, $categoria) {

 try {
  $idCategoria = $categoria->getIdCategoria();
  $nombre = $categoria->getNombre();
  $sql = "UPDATE categorias SET nombre = :nombre WHERE categorias.id_categoria = :id_categoria ";
  $stmt = $entityManager->getConnection()->prepare($sql);
  $stmt->bindParam(':id_categoria', $idCategoria);
  $stmt->bindParam(':nombre', $nombre);
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

public function actualizarNombre($entityManager, $repositorio, $nombre, $idCategoria) {

  try {
    $categoria= $repositorio->find($idCategoria);

    if ($categoria) {
      $sql = "UPDATE categorias SET nombre = :nombre WHERE categorias.id_categoria = :id_categoria ";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':nombre', $nombre);
      $stmt->bindParam(':id_categoria', $idCategoria);
      $stmt->execute();
      $categoria->setNombre($nombre);
      $entityManager->flush();

      return true;
    } else {
      return false;
    }
  } catch(\Exception $e) {
    return false;
  }
}


}
