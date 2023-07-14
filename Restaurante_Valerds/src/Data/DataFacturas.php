<?php

namespace App\Data;

use App\Entity\Facturas;
use App\Form\FacturasType;

class DataFacturas{

  public function obtenerListadoFacturas($entityManager) {

    try {
      $facturas = array();
      $sql = "call sp_get_todas_facturass();";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      if ($result) {
        foreach ($result as $factura) {
         $facturas[] =
         array(
          'id_factura' => $factura['id_factura'],
          'id_pedido'=> $factura['id_pedido'],
          'cajero' =>$factura['cajero'],
          'nombre' =>$factura['nombre'],
          'monto' => $factura['monto'],
          'fecha' => $factura['fecha'],
        );
       }
       $stmt->closeCursor();
       return $facturas;
     }else{
       return null;
     }
   } catch (\Exception $e) {
    return null;
  }
}

public function obtenerDetalleFactura($entityManager, $idFactura) {

  try {
    $facturas = array();
    $sql = "call sp_get_detalle_factura('.$idFactura.');";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if ($result) {
      foreach ($result as $factura) {
       $facturas[] =
       array(
        'id_factura'=> $factura['id_factura'],
        'fecha'=> $factura['fecha'],
        'nombreCliente'=> $factura['nombreCliente'],
        'nombreCajero'=> $factura['nombreCajero'],
        'monto'=> $factura['monto'],
        'id_pedido'=> $factura['id_pedido'],
        'estado'=> $factura['estado'],
        'numeroMesa'=> $factura['numeroMesa'],
        'id_menu'=> $factura['id_menu'],
        'id_categoria'=> $factura['id_categoria'],
        'categorias'=> $factura['categorias'],
        'nombre'=> $factura['nombre'],
        'descripcion'=> $factura['descripcion'],
        'precioProducto'=> $factura['precioProducto'],
        'precio'=> $factura['precio'],
        'subTotal'=> $factura['subTotal'],
        'cantidadProducto'=> $factura['cantidadProducto']);
     }
     return $facturas;
   }else{
     return null;
   }
 } catch (\Exception $e) {
  return null;
}
}

public function obtenerDetalleFacturaTotal($entityManager, $idFactura) {

  try {
    $totalFacturas = array();
    $sql = "call sp_get_total_factura('.$idFactura.');";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if ($result !== false and $result > 0 and $result!== null ) {
      foreach ($result as $factura) {
       $totalFacturas[] =
       array(
        'total'=>$factura['total']);
    }
     return $totalFacturas;
   }else{
     return null;
   }
 } catch (\Exception $e) {
  return null;
}
}
}
