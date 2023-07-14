<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovInventario
 *
 * @ORM\Table(name="mov_inventario")
 * @ORM\Entity
 */
class MovInventario
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_usuario", type="string", length=300, nullable=false)
     */
    private $nombreUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="producto", type="string", length=300, nullable=false)
     */
    private $producto;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_anterior", type="integer", nullable=false)
     */
    private $stockAnterior;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_actual", type="integer", nullable=false)
     */
    private $stockActual;

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(string $nombreUsuario): self
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    public function getProducto(): ?string
    {
        return $this->producto;
    }

    public function setProducto(string $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getStockAnterior(): ?int
    {
        return $this->stockAnterior;
    }

    public function setStockAnterior(int $stockAnterior): self
    {
        $this->stockAnterior = $stockAnterior;

        return $this;
    }

    public function getStockActual(): ?int
    {
        return $this->stockActual;
    }

    public function setStockActual(int $stockActual): self
    {
        $this->stockActual = $stockActual;

        return $this;
    }


}
