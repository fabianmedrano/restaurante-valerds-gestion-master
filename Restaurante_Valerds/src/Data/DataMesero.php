<?php

namespace App\Data;

use App\Entity\Inventario;
use App\Form\InventarioType;

class DataMesero {

  public function registrarPedido($entityManager, $pedido, $mesa, $idUsuario) {
   try {
    
    $sql = "call sp_insert_pedido(:id_usuario, :numeroMesa);";
    $entityManager->getConnection()->beginTransaction();
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->bindParam(':numeroMesa', $mesa);
    $stmt->execute();
    $result = $stmt->fetch();
    $idPedido = $result['idPedido'];
    $stmt->closeCursor();

    $sql = "call sp_insert_detalle_pedido(:id_menu, :id_pedido, :cantidad);";

    foreach ($pedido as $platillo) {
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':id_menu', $platillo->id);
      $stmt->bindParam(':id_pedido', $idPedido);
      $stmt->bindParam(':cantidad', $platillo->cantidad);
      $resultado=$stmt->execute();
      print_r($resultado);
    }
    $entityManager->getConnection()->commit();
    if($resultado){
      return true;
    }else{
      return false;
    }
  } catch(\Exception $e) {
    return false;
  }
}
}

