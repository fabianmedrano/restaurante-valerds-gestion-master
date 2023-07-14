<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pedidos
 *
 * @ORM\Table(name="pedidos", indexes={@ORM\Index(name="id_usuario", columns={"id_usuario"})})
 * @ORM\Entity
 */
class Pedidos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_pedido", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPedido;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=true)
     */
    private $idUsuario;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroMesa", type="string", length=300, nullable=false)
     */
    private $numeromesa;

    public function getIdPedido(): ?int
    {
        return $this->idPedido;
    }
    public function setIdPedido(?int $idPedido): self
    {
        $this->idPedido = $idPedido;
        return $this;
    }
    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }
  
    public function setIdUsuario(?int $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getNumeromesa(): ?string
    {
        return $this->numeromesa;
    }

    public function setNumeromesa(string $numeromesa): self
    {
        $this->numeromesa = $numeromesa;

        return $this;
    }


}
