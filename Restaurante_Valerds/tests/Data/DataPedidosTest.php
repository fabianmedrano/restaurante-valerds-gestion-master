<?php

namespace Tests;

use App\Data\DataPedidos;
use App\Entity\Pedidos;
use App\Entity\MenuPedidos;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataPedidosTest extends WebTestCase
{
	private $entityManager;
	private $repository;
	private $dp;

	private $entityManagerMenu;
	private $repositoryMenu;
	private $mp;

	public function setUp() {
		$this->dp = new DataPedidos();
		$kernel = self::bootKernel();
		$this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
		$this->repository = $kernel->getContainer()->get('doctrine')->getRepository(Pedidos::class);

		$kernelMenu = self::bootKernel();
		$this->entityManagerMenu = $kernelMenu->getContainer()->get('doctrine')->getManager();
		$this->repositoryMenu = $kernelMenu->getContainer()->get('doctrine')->getRepository(MenuPedidos::class);

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

	public function test1ObtenerPedidoMesa() {
		$resultado = $this->dp->obtenerPedidoMesa($this->entityManager,1);
		$this->assertNotNull($resultado);
	}

	public function test2ObtenerListadoPlatillosPedido() {
		$resultado = $this->dp->obtenerListadoPlatillosPedido($this->entityManager,5);
		$this->assertNotNull($resultado);
	}

	public function test3OobtenerTotalPedido() {
		$resultado = $this->dp->obtenerTotalPedido($this->entityManager,5);
		$this->assertNotNull($resultado);
	}

	public function test4ObtenerListadoPedidosMesa() {
		$resultado = $this->dp->obtenerListadoPedidosMesa($this->entityManager, 1);
		$this->assertNotNull($resultado);
	}

}
