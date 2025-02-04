<?php

namespace App\Data;

use App\Entity\Inventario;
use App\Form\InventarioType;

class DataMesero {
public function registrarPedido($entityManager, $pedido, $mesa, $idUsuario) {
  try {
      $sqlInsertPedido = "INSERT INTO `pedidos` (id_usuario, estado, numeroMesa) VALUES (:id_usuario, :estado, :numeroMesa)";
      
      $entityManager->getConnection()->beginTransaction();
      
      $stmt = $entityManager->getConnection()->prepare($sqlInsertPedido);
      $stmt->bindValue(':id_usuario', $idUsuario);
      $stmt->bindValue(':numeroMesa', $mesa);
      $stmt->bindValue(':estado', false);
      $stmt->execute();
      $idPedido = $entityManager->getConnection()->lastInsertId();

      $sqlInsertMenuPedido = "INSERT INTO menu_pedidos (id_menu, id_pedido, cantidad) VALUES (:id_menu, :id_pedido, :cantidad)";
      $stmt = $entityManager->getConnection()->prepare($sqlInsertMenuPedido);

      foreach ($pedido as $platillo) {
          $stmt->bindValue(':id_menu', $platillo->id);
          $stmt->bindValue(':id_pedido', $idPedido);
          $stmt->bindValue(':cantidad', $platillo->cantidad);
          $stmt->execute();
      }

      $entityManager->getConnection()->commit();
      return $idPedido; 
  } catch (\Exception $e) {
      $entityManager->getConnection()->rollBack(); 
      error_log($e->getMessage());
      return false; 
  }
}
}

