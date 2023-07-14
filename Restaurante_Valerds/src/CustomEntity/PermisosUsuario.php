<?php

namespace App\CustomEntity;

class PermisosUsuario
{
    private $idPermiso;
    private $nombre;
    private $estado;

    public function getIdPermiso()
    {
        return $this->idPermiso;
    }

    public function setIdPermiso($idPermiso)
    {
        $this->idPermiso = $idPermiso;
        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }
  }
