<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosUsuarios
 *
 * @ORM\Table(name="permisos_usuarios")
 * @ORM\Entity
 */
class PermisosUsuarios
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_permiso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idPermiso;

    /**
     * @var int
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUsuario;

    public function getIdPermiso(): ?int
    {
        return $this->idPermiso;
    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function setIdPermiso(int $idPermiso): self
    {
        $this->idPermiso = $idPermiso;
        return $this;
    }

    public function setIdUsuario(int $idUsuario): self
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }
}
