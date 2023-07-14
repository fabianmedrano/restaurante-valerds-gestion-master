<?php

namespace App\Data;

use App\Entity\Permisos;
use App\CustomEntity\PermisosUsuario;
use App\Entity\PermisosUsuarios;
use App\Form\PermisosType;

class DataPermisos {

  public function obtenerPermisos($entityManager) {

    try {
      $permisos = array();
      $sql = "call sp_get_permisos();";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $permiso) {
        $permisoUsuario = new PermisosUsuario();
        $permisoUsuario->setIdPermiso($permiso['id_permiso']);
        $permisoUsuario->setNombre($permiso['nombre']);
        $permisoUsuario->setEstado(false);
        array_push($permisos, $permisoUsuario);
      }
      $stmt->closeCursor();
      if($result){
      return $permisos;
    }else{
      return null;
    }
    } catch (\Exception $e) {
      return null;
    }
  }

  public function obtenerPermisosUsuario($idUsuario, $entityManager) {

    try {
      $permisos = array();
      $sql = "call sp_get_permisos_usuario(:id_usuario);";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':id_usuario', $idUsuario);
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $permiso) {
        $permisoUsuario = new PermisosUsuario();
        $permisoUsuario->setIdPermiso($permiso['id_permiso']);
        $permisoUsuario->setNombre($permiso['nombre']);

        if ($permiso['id_usuario'] == null) {
            $permisoUsuario->setEstado(false);
        } else {
            $permisoUsuario->setEstado(true);
        }
        array_push($permisos, $permisoUsuario);
      }
      $stmt->closeCursor();
      return $permisos;
    } catch (\Exception $e) {
      return null;
    }
  }
}
