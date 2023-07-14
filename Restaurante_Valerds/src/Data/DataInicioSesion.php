<?php

namespace App\Data;

use App\CustomEntity\Login;
use App\Form\LoginType;

class DataInicioSesion {

  public function iniciarSesion($usuario, $repositorio) {

    try {
      $nombreUsuario = $usuario->getUsuario();
      $contrasena = $usuario->getContrasena();

      $usuarioLogeo = $repositorio->findOneBy([
        'usuario' => $nombreUsuario,
        'contrasena' => $contrasena,
        'estado' => '1'
      ]);
      if($usuarioLogeo){
        return $usuarioLogeo;
      }else{
       return null;
     }
   } catch (\Exception $e) {
    return null;
  }
}
}