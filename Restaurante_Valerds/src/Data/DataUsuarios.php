<?php

namespace App\Data;

use App\Entity\Usuarios;
use App\Entity\PermisosUsuarios;
use App\Form\UsuariosType;

class DataUsuarios {

  public function obtenerListadoUsuarios($entityManager) {

    try {
      $usuarios = array();
      $sql = "SELECT * FROM usuarios" ;
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

      $sql = " INSERT INTO usuarios (usuario, estado, nombre, contrasena, correo) VALUES ( :usuario,:contrasena,:estado,:nombre, :correo) ";
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

      $sql = "UPDATE usuarios SET usuario = :usuario, estado = :estado, nombre = :nombre, contrasena = :contrasena, correo = :correo WHERE usuarios.id_usuario = :id_usuario";
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
