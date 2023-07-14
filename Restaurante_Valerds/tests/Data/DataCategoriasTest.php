<?php

namespace Tests;

use App\Data\DataCategorias;
use App\Entity\Categorias;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataCategoriasTest extends WebTestCase
{
  private $entityManager;
  private $repository;
  private $dc;

  public function setUp() {
    $this->dc = new DataCategorias();
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    $this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Categorias::class);
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
  public function test1ObtenerListadoCategorias() {
    $resultado = $this->dc->obtenerListadoCategorias($this->entityManager);
    $this->assertNotNull($resultado);
  }

  public function test2RegistrarCategoria() {
    $resultado = $this->dc->registrarCategoria($this->entityManager, null, "categoriaTest");
    $this->assertTrue($resultado);
  }

  public function test3EditarCategoria() {
    $categoria = $this->repository->findOneBy(['nombre' => 'categoriaTest']);

    $resultado = $this->dc->editarCategoria($this->entityManager, $categoria);
    $this->assertTrue($resultado);
  }
  //////////////////////////////////////////////////////////////////////////////
  //Cambiar el numero de este test para que sea el ultimo en ejecutarse
  public function test4EliminarCambios() {
    $categoria = $this->repository->findOneBy(['nombre' => 'categoriaTest']);

    if ($categoria) {
      $this->entityManager->remove($categoria);
      $this->entityManager->flush();
    }
    $this->assertTrue(true);
  }
}
