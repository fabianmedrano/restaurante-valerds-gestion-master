<?php

namespace Tests;

use App\Data\DataMenu;
use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataMenuTest extends WebTestCase
{
  
  private $entityManager;
  private $repository;
  private $di;

  public function setUp() {
    $this->di = new DataMenu();
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    $this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Menu::class);
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

    public function test1ObtenerListadoMenu() {
    $resultado = $this->di->obtenerListadoMenu($this->entityManager);
    $this->assertNotNull($resultado);
  }
}