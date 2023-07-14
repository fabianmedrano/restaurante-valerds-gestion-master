<?php

namespace Tests;

use App\Data\DataFacturas;
use App\Entity\Facturas;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataFacturasTest extends WebTestCase
{
	private $entityManager;
	private $repository;
	private $df;

	public function setUp() {
		$this->df = new DataFacturas();
		$kernel = self::bootKernel();
		$this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
		$this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Facturas::class);
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
	public function test1ObtenerListadoFacturas() {
		$resultado = $this->df->obtenerListadoFacturas($this->entityManager);
		$this->assertNotNull($resultado);
	}

	public function test2ObtenerDetalleFactura() {
		$resultado = $this->df->obtenerDetalleFactura($this->entityManager, 87);
		$this->assertNotNull($resultado);
	}

	public function test3ObtenerDetalleFacturaTotal() {
		$resultado = $this->df->obtenerDetalleFactura($this->entityManager, 87);
		$this->assertNotNull($resultado);
	}
}
