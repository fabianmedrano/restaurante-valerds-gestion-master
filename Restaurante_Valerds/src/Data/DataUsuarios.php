<?php

namespace App\Data;

use App\Entity\Usuarios;
use App\Entity\PermisosUsuarios;
use App\Form\UsuariosType;

class DataUsuarios {

  public function obtenerListadoUsuarios($entityManager) {

    try {
      $usuarios = array();
      $sql = "call sp_get_usuarios();";
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $usuario) {
         $usuarios[] =
               array(
              'idUsuario' => $usuario['id_usuario'],
               'usuario' => $usuario['usuario'],
               'contrasena' => $usuario['contrasena'],
               'estado' => $usuario['estado'],
               'nombre' => $usuario['nombre'],
               'correo' => $usuario['correo'],
          );
      }
      $stmt->closeCursor();
      return $usuarios;
    } catch (\Exception $e) {
      return null;
    }
  }

  public function registrarUsuario($entityManager, $usuario) {

    try {
      $nombreUsuario = $usuario->getUsuario();
      $contrasena = $usuario->getContrasena();
      $estado = true;
      $nombre = $usuario->getNombre();
      $correo = $usuario->getCorreo();

      $sql = "call sp_insert_usuarios(:usuario,:contrasena,:estado,:nombre,:correo, @idUsuario);";
      $entityManager->getConnection()->beginTransaction();
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':usuario', $nombreUsuario);
      $stmt->bindParam(':contrasena', $contrasena);
      $stmt->bindParam(':estado', $estado);
      $stmt->bindParam(':nombre', $nombre);
      $stmt->bindParam(':correo', $correo);
      $stmt->execute();
      $result = $stmt->fetch();
      $idUsuario = $result['idUsuario'];
      $stmt->closeCursor();

      //asignar nuevos
      $permisosUsuarioLocal = $usuario->getPermisosUsuario();

      foreach ($permisosUsuarioLocal as $permisoUL) {

        if ($permisoUL->getEstado()) {
          $permiso = new PermisosUsuarios();
          $permiso->setIdPermiso($permisoUL->getIdPermiso());
          $permiso->setIdUsuario($idUsuario);
          $entityManager->persist($permiso);
          $entityManager->flush();
        }
      }
      $entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $entityManager->getConnection()->rollback();
      return false;
    }
  }

  public function editarUsuario($entityManager, $usuario) {

    try {
      $idUsuario = $usuario->getIdUsuario();
      $nombreUsuario = $usuario->getUsuario();
      $contrasena = $usuario->getContrasena();
      $estado = $usuario->getEstado();
      $nombre = $usuario->getNombre();
      $correo = $usuario->getCorreo();

      $sql = "call sp_update_usuario(:id_usuario,:usuario,:contrasena,:estado,:nombre,:correo);";
      $entityManager->getConnection()->beginTransaction();
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindParam(':id_usuario', $idUsuario);
      $stmt->bindParam(':usuario', $nombreUsuario);
      $stmt->bindParam(':contrasena', $contrasena);
      $stmt->bindParam(':estado', $estado);
      $stmt->bindParam(':nombre', $nombre);
      $stmt->bindParam(':correo', $correo);
      $stmt->execute();
      //remover permisos
      $repositorio = $entityManager->getRepository(PermisosUsuarios::class);
      $permisosUsuarioDB = $repositorio->findBy(['idUsuario' => $idUsuario]);

      foreach ($permisosUsuarioDB as $permiso) {
        //Eviar quitar permiso de admin
        if ($permiso->getIdPermiso() != 3) {
          $entityManager->remove($permiso);
          $entityManager->flush();
        }
      }
      //asignar nuevos
      $permisosUsuarioLocal = $usuario->getPermisosUsuario();

      if ($permisosUsuarioLocal) {

        foreach ($permisosUsuarioLocal as $permisoUL) {

          if ($permisoUL->getEstado()) {
            $permiso = new PermisosUsuarios();
            $permiso->setIdPermiso($permisoUL->getIdPermiso());
            $permiso->setIdUsuario($idUsuario);
            $entityManager->persist($permiso);
            $entityManager->flush();
          }
        }
      }
      $entityManager->getConnection()->commit();
      return true;

    } catch (\Exception $e) {
      $entityManager->getConnection()->rollback();
      return false;
    }
  }
}
