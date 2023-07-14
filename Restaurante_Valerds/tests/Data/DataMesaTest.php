<?php

namespace Tests;

use App\Data\DataMesa;
use App\Entity\Mesa;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataMesaTest extends WebTestCase
{
	private $entityManager;
	private $repository;
	private $dm;

	public function setUp() {
		$this->dm = new DataMesa();
		$kernel = self::bootKernel();
		$this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
		$this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Mesa::class);
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
	public function test1ObtenerMesas() {
		$resultado = $this->dm->obtenerMesas($this->repository);
		$this->assertNotNull($resultado);
	}

	public function test2ObtenerMesasPedidosPendientes() {
		$resultado = $this->dm->obtenerMesasPedidosPendientes($this->entityManager);
		$this->assertNotNull($resultado);
	}

	public function test3ObtenerMesa() {
		$resultado = $this->dm->obtenerMesa($this->entityManager,3);
		$this->assertNotNull($resultado);
	}

		public function test4ObtenerNumeroMesa() {
		$resultado = $this->dm->obtenerNumeroMesa($this->entityManager);
		$this->assertNotNull($resultado);
	}


}