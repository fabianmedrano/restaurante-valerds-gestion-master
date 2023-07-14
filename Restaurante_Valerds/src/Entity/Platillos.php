<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Platillos
 *
 * @ORM\Table(name="platillos", indexes={@ORM\Index(name="id_categoria", columns={"id_categoria"})})
 * @ORM\Entity
 */
class Platillos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_platillo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPlatillo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_categoria", type="integer", nullable=true)
     */
    private $idCategoria;

    public function getIdPlatillo(): ?int
    {
        return $this->idPlatillo;
    }

    public function getIdCategoria(): ?int
    {
        return $this->idCategoria;
    }

    public function setIdCategoria(?int $idCategoria): self
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }


}
