<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acompanamientos
 *
 * @ORM\Table(name="acompanamientos")
 * @ORM\Entity
 */
class Acompanamientos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_acompanamiento", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAcompanamiento;

    public function getIdAcompanamiento(): ?int
    {
        return $this->idAcompanamiento;
    }


}
