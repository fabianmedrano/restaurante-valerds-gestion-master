<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mesa
 *
 * @ORM\Table(name="mesa")
 * @ORM\Entity
 */
class Mesa
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mesa", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMesa;

    public function getIdMesa(): ?int
    {
        return $this->idMesa;
    }


}
