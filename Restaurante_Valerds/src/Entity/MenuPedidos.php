<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuPedidos
 *
 * @ORM\Table(name="menu_pedidos", indexes={@ORM\Index(name="id_pedido", columns={"id_pedido"})})
 * @ORM\Entity
 */
class MenuPedidos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_menu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idMenu = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="id_pedido", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idPedido = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="precio", type="integer", nullable=false)
     */
    private $precio;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
    private $cantidad;

    public function getIdMenu(): ?int
    {
        return $this->idMenu;
    }
    public function setIdMenu(int $idMenu): self
    {
        $this->idMenu = $idMenu;

        return $this;
    }

    public function getIdPedido(): ?int
    {
        return $this->idPedido;
    }

     public function setIdPedido(int $idPedido): self
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }


}
