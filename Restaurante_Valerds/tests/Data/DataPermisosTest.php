<?php

namespace Tests;

use App\Data\DataPermisos;
use App\Entity\Permisos;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataPermisosTest extends WebTestCase
{
  private $entityManager;
  private $repository;
  private $di;

  public function setUp() {
    $this->dp = new DataPermisos();
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    $this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Permisos::class);
  }

  public function tearDown()
  {
    parent::tearDown();

    if($this->entityManager->getConnection()->isTransactionActive()) {
      $this->entityManager->getConnection()->rollback();
    }
    $this->entityManager->close();
    $this->entityManager = null;
  }

  public function test1ObtenerPermisos() {
    $resultado = $this->dp->obtenerPermisos($this->entityManager);
    $this->assertNotNull($resultado);
  }
}