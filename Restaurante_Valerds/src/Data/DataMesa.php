<?php

namespace App\Data;

use App\Entity\Mesa;
use App\Form\MesaType;

use Symfony\Component\Config\Definition\Exception\Exception;

class DataMesa {

  public function obtenerMesas($repositorio) {

    try {
      $mesas = $repositorio->findAll();
      if($mesas){ 
        return $mesas;
      }else{
       return null;
     }
   } catch (\Exception $e) {
    return null;
  }
}
public function obtenerMesasPedidosPendientes($entityManager){

  try {
    $mesas = array();
    $sql = "call sp_get_mesa_pendientes();";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result) {
      foreach ($result as $mesa) {
       $mesas[] =
       array(
        'mesa' => $mesa['mesa'],
        'pedidos'=> $mesa['pedidos']
      );
     }
     $stmt->closeCursor();
     return $mesas;
   }else{
     return null;
   }
 } catch (\Exception $e) {
  return null;
}
}

public function obtenerMesa($entityManager,$idPedido) {
  try {
    $sql = "call sp_get_mesa_pedido(:idPedido);";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->bindParam(':idPedido', $idPedido);
    $stmt->execute();
    $result = $stmt->fetch();
    $numeroMesa= $result['numeroMesa'];
    
    if($stmt){
      $stmt->closeCursor();
      return $numeroMesa;
    }else{
      return false;
    }
  } catch (\Exception $e) {
    return false;
  }

}

public function obtenerNumeroMesa($entityManager) {
  try {
    $sql = "call sp_get_cant_mesas();";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $numeroMesa= $result['count(*)'];
    $stmt->closeCursor();
    if($numeroMesa){
      return $numeroMesa;
    }else{
      return false;
    }
  } catch (\Exception $e) {
    return false;
  }
}

public function ModificarNumMesa($entityManager, $numero) {
 try {
   $sql = "call sp_insert_mesa(:numero);";
   $stmt = $entityManager->getConnection()->prepare($sql);
   $stmt->bindParam(':numero', $numero);
   $stmt->execute();
   return true;
 } catch (\Exception $e) {
   return false;
 }
}


}
