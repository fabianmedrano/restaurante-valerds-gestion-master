<?php

namespace Tests;

use App\Data\DataUsuarios;
use App\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataUsuariosTest extends WebTestCase
{
  private $entityManager;
  private $repository;
  private $du;

  public function setUp() {
    $this->du = new DataUsuarios();
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    $this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Usuarios::class);
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
  public function test1ObtenerListadoUsuarios() {
    $resultado = $this->du->obtenerListadoUsuarios($this->entityManager);
    $this->assertNotNull($resultado);
  }

  public function test2RegistrarUsuario() {
    $usuario = new Usuarios();
    $usuario->setUsuario("usuarioTest");
    $usuario->setContrasena("23123131asdasdasdadad");
    $usuario->setEstado(true);
    $usuario->setNombre("asdadsadadaddads");
    $usuario->setCorreo("asddaasdasd@gmail.com");
    $resultado = $this->du->registrarUsuario($this->entityManager, $usuario);
    $this->assertTrue($resultado);
  }

  public function test3EditarUsuario() {
    $usuario = $this->repository->findOneBy(['usuario' => 'usuarioTest']);

    $resultado = $this->du->editarUsuario($this->entityManager, $usuario);
    $this->assertTrue($resultado);
  }
  //////////////////////////////////////////////////////////////////////////////
  //Cambiar el numero de este test para que sea el ultimo en ejecutarse
  public function test4EliminarCambios() {
    $usuario = $this->repository->findOneBy(['usuario' => 'usuarioTest']);

    if ($usuario) {
      $this->entityManager->remove($usuario);
      $this->entityManager->flush();
    }
    $this->assertTrue(true);
  }
}
