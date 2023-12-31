<?php
namespace App\Data;

use App\Entity\Pedidos;
use App\Form\PedidosType;

class DataPedidos {

  public function obtenerPedidoMesa($entityManager,$mesa)
  {

    try {
      $sql = "call sp_get_idPedido_mesa(:mesa);";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':mesa', $mesa);
      $stmt->execute();
      $result = $stmt->fetch();
      $idpedido= $result['id_pedido'];
      if ($idpedido) {
       $stmt->closeCursor();
       return $idpedido;
     } else {
       return null;
     }
    } catch (\Exception $e) {
      return false;
    }
  }



  public function obtenerMesaidPedido($entityManager,$idPedido)
  {

    try {
      $sql = "call sp_get_mesa_idPedido(:idPedido);";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':idPedido', $idPedido);
      $stmt->execute();
      $result = $stmt->fetch();
      $mesa= $result['mesa'];
      if ($mesa) {
       $stmt->closeCursor();
       return $mesa;
     } else {
       return null;
     }
    } catch (\Exception $e) {
      return false;
    }
  }




  public function obtenerListadoPlatillosPedido($entityManager, $idPedido) {
    try {
      $platillos = array();
      $sql = "call sp_get_detalle_pedido(:idPedido);";

      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':idPedido', $idPedido);
      $stmt->execute();

      $result = $stmt->fetchAll();

      if ($result) {
        foreach ($result as $platillo) {
         $platillos[] =
         array(
          'idPedido' => $platillo['id_pedido'],
          'idMenu' => $platillo['id_menu'],
          'nombre' => $platillo['nombre'],
          'precio' => $platillo['precio'],
          'descripcion' => $platillo['descripcion'],
          'cantidad' => $platillo['cantidad'],
        );
       }

       $stmt->closeCursor();
       return $platillos;
      }else{
       return null;
      }
    } catch (\Exception $e) {
      return null;
    }
  }

  
  public function insertsubpedido($entityManager, $pedido, $mesa,$idUsuario ,$idPedidoAnterior/*$precio,$cantidad,*/ ) {

    $sql = "call sp_insert_pedido(:id_usuario, :numeroMesa);";
     $entityManager->getConnection()->beginTransaction();
     $stmt = $entityManager->getConnection()->prepare($sql);
     $stmt->bindParam(':id_usuario', $idUsuario);
     $stmt->bindParam(':numeroMesa', $mesa);
     $stmt->execute();
     $result = $stmt->fetch();
     $idPedido = $result['idPedido'];
     $stmt->closeCursor();
     $sql = "call sp_insert_update_subpedido(:id_menu, :id_pedido, :cantidad ,:idPedidoAnterior);"; /**problema con procedimiento */
     
     foreach ($pedido as $platillo) {
      $stmt = $entityManager->getConnection()->prepare($sql);
       $stmt->bindParam(':id_menu', $platillo->id);
       $stmt->bindParam(':id_pedido', $idPedido);
       $stmt->bindParam(':cantidad', $platillo->cantidad);
       $stmt->bindParam(':idPedidoAnterior', $idPedidoAnterior);
       $stmt->execute();
     }
     $entityManager->getConnection()->commit();
   
   
   
     try {   return true;
     } catch(\Exception $e) {
       return false;
     }
   
   }


  public function eliminarDetallePedido($entityManager,$idMenu,$idPedido) {

    try {
      $sql = "call sp_delete_menu_pedidos(:idMenu,:idPedido);";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':idMenu',$idMenu);
      $stmt->bindParam(':idPedido', $idPedido);

      $stmt->execute();
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }


  public function obtenerTotalPedido($entityManager, $idPedido) {

    try {
      $total = array();

      $sql = "call sp_get_total_pedido(:idPedido);";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':idPedido', $idPedido);
      $stmt->execute();
      $result = $stmt->fetch();
      $total= $result['total'];
      if ($total !== false and  $total > 0 and  $total!== null ) {
        $stmt->closeCursor();
        return $total;
      }else{
        return null;
      }
    } catch (\Exception $e) {
      return null;
    }
  }


  public function obtenerListadoPedidosMesa($entityManager, $mesa) {

    try {
      $pedidos = array();
      $sql = "call sp_get_pedido_pendiente_mesa(:mesa);";

      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':mesa', $mesa);
      $stmt->execute();
      $result = $stmt->fetchAll();
      if($result){
        foreach ($result as $pedido) {
         $pedidos[] =
         array(
           'total' => $pedido['total'],
           'menu' => $pedido['menu'],
           'numeroMesa' => $pedido['numeroMesa'],
           'idPedido' => $pedido['id_pedido'],
         );
       }

       $stmt->closeCursor();
       return $pedidos;
      }else{
       return null;
      }
    } catch (\Exception $e) {
      return null;
    }
  }
  public function agregarAPedido($entityManager, $pedido, $mesa,$idPedido) {
    try{
        // insert into detalle pedido donde es = id pedido
        $sql = "call sp_insert_detalle_pedido(:id_menu, :id_pedido, :cantidad);";
        foreach ($pedido as $platillo) {
          print_r ( $platillo->id);
          $stmt = $entityManager->getConnection()->prepare($sql);
          $stmt->bindParam(':id_menu', $platillo->id);
          $stmt->bindParam(':id_pedido', $idPedido);
          $stmt->bindParam(':cantidad', $platillo->cantidad);
          $resultado =$stmt->execute();
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

  public function eliminarPedido($entityManager, $pedido) {
    try{

      $sql = "call sp_update_estado_pedido(:idPedido);";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':idPedido', $pedido);
      $stmt->execute();
      $stmt->closeCursor();


      $stmt->closeCursor();
      return true;
    } catch(\Exception $e) {
      return false;
    }
  }

  public function mezclarPedidos($entityManager, $pedido , $idPedido , $idPedido2 ) {
    try {
        $entityManager->getConnection()->beginTransaction();
        $sql = "call sp_modificar_menu_pedidos(:idMenu, :idPedido ,:idPedido2 ,:cantidad);"; 

        foreach ($pedido as $platillo) {
          $stmt = $entityManager->getConnection()->prepare($sql);
          $stmt->bindParam(':idMenu', $platillo->id);
          $stmt->bindParam(':idPedido', $idPedido);
          $stmt->bindParam(':idPedido2', $idPedido2);
          $stmt->bindParam(':cantidad', $platillo->cantidad);

          $resultado=$stmt->execute();
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

  public function unirTodo($entityManager, $pedidos) {
    
    $primero = false;
    try{
      $entityManager->getConnection()->beginTransaction();
      $sql = "call sp_unir_pedidos_mesa(:pedidoDestino, :pedidoOrigen);"; 
  
      foreach ($pedidos as $pedido) {
        if($primero){
          $stmt = $entityManager->getConnection()->prepare($sql);
          $stmt->bindParam(':pedidoDestino',$pedidos[0]);
          $stmt->bindParam(':pedidoOrigen',  $pedido);
          $resultado=$stmt->execute();
        }else{
          $primero= true;
        }
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


  public function facturar($entityManager, $pedido , $usuario , $nombre) {
    try {
      $sql = "call sp_facturar( :idPedido , :idUsuario , :nombreCliente );";
      $stmt = $entityManager->getConnection()->prepare($sql );
      
      $stmt->bindParam(':idPedido', $pedido);
      $stmt->bindParam(':idUsuario', $usuario);
      $stmt->bindParam(':nombreCliente', $nombre);
      
      $resultado=$stmt->execute();
      $stmt->closeCursor();

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