<?php

namespace App\Data;

use App\Entity\Mesa;
use App\Form\MesaType;

use Symfony\Component\Config\Definition\Exception\Exception;

class DataMesa {
 //NOTE: i still don't know how work this, it's count the register like a table, but register could have values  front 0 to 200, i don't know if that is de number of chairs  
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
    $sql = "SELECT numeroMesa ,id_pedido FROM pedidos where estado = 0 ;";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result) {
      foreach ($result as $mesa) {
       $mesas[] =
       array(
        'mesa' => $mesa['numeroMesa'],
        'pedidos'=> $mesa['id_pedido']
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
    $sql = "SELECT numeroMesa FROM pedidos where id_pedido = :idPedido;";
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
    $sql = "select count(*) from mesa;";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $numeroMesa = $result;
    $numeroMesa =(empty($numeroMesa))?0:$numeroMesa;
    if($numeroMesa ) {
      return $numeroMesa['count(*)'];
    }else{
      return false;
    }
  } catch (\Exception $e) {
    return false;
  }
}

public function ModificarNumMesa($entityManager, $numero) {
 try {
   $sql = "INSERT INTO `mesa`(`id_mesa`) VALUES (:numero);";
   $stmt = $entityManager->getConnection()->prepare($sql);
   $stmt->bindParam(':numero', $numero);
   $stmt->execute();
   return true;
 } catch (\Exception $e) {
   return false;
 }
}


}
