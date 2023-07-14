<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permisos
 *
 * @ORM\Table(name="permisos")
 * @ORM\Entity
 */
class Permisos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_permiso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPermiso;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=300, nullable=false)
     */
    private $nombre;

    public function getIdPermiso(): ?int
    {
        return $this->idPermiso;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }


}
