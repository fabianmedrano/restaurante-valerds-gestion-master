<?php

namespace Tests;

use App\Data\DataInventario;
use App\Entity\Inventario;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataInventarioTest extends WebTestCase
{
  private $entityManager;
  private $repository;
  private $di;

  public function setUp() {
    $this->di = new DataInventario();
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    $this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Inventario::class);
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
  //////////////////////////////////////////////////////////////////////////////
  public function test1ObtenerListadoInventario() {
    $resultado = $this->di->obtenerListadoInventario($this->entityManager);
    $this->assertNotNull($resultado);
  }

  public function test2RegistrarInventario() {
    $inventario = new Inventario();
    $inventario->setNombre("inventarioTest");
    $inventario->setDescripcion("descripcion");
    $inventario->setStock("10");
    $resultado = $this->di->registrarInventario($this->entityManager, $inventario);
    $this->assertTrue($resultado);
  }

  public function test3EditarInventario() {
    $inventario = $this->repository->findOneBy(['nombre' => 'inventarioTest']);

    $resultado = $this->di->editarInventario($this->entityManager, $inventario);
    $this->assertTrue($resultado);
  }
  //////////////////////////////////////////////////////////////////////////////
  //Cambiar el numero de este test para que sea el ultimo en ejecutarse
  public function test4EliminarCambios() {
    $inventario = $this->repository->findOneBy(['nombre' => 'inventarioTest']);

    if ($inventario) {
      $this->entityManager->remove($inventario);
      $this->entityManager->flush();
    }
    $this->assertTrue(true);
  }
}
