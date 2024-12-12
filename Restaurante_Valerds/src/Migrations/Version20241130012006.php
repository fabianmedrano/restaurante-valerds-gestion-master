<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241130012006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acompanamientos (id_acompanamiento INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id_acompanamiento)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorias (id_categoria INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(300) NOT NULL, UNIQUE INDEX nombre (nombre), PRIMARY KEY(id_categoria)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facturas (id_factura INT AUTO_INCREMENT NOT NULL, id_pedido INT DEFAULT NULL, id_usuario INT DEFAULT NULL, nombre VARCHAR(300) DEFAULT NULL, monto INT DEFAULT NULL, fecha DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX id_usuario (id_usuario), UNIQUE INDEX id_pedido (id_pedido), PRIMARY KEY(id_factura)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventario (id_articulo INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(300) NOT NULL, descripcion VARCHAR(300) NOT NULL, stock INT NOT NULL, UNIQUE INDEX nombre (nombre), PRIMARY KEY(id_articulo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id_menu INT AUTO_INCREMENT NOT NULL, id_categoria INT DEFAULT NULL, nombre VARCHAR(300) NOT NULL, descripcion VARCHAR(300) NOT NULL, precio INT NOT NULL, estado TINYINT(1) DEFAULT NULL, INDEX id_categoria (id_categoria), PRIMARY KEY(id_menu)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_pedidos (id_menu INT NOT NULL, id_pedido INT NOT NULL, precio INT NOT NULL, cantidad INT NOT NULL, INDEX id_pedido (id_pedido), PRIMARY KEY(id_menu, id_pedido)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mesa (id_mesa INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id_mesa)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mov_inventario (fecha DATETIME NOT NULL, nombre_usuario VARCHAR(300) NOT NULL, producto VARCHAR(300) NOT NULL, stock_anterior INT NOT NULL, stock_actual INT NOT NULL, PRIMARY KEY(fecha)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos (id_pedido INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, estado TINYINT(1) NOT NULL, numeroMesa VARCHAR(300) NOT NULL, INDEX id_usuario (id_usuario), PRIMARY KEY(id_pedido)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permisos (id_permiso INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(300) NOT NULL, PRIMARY KEY(id_permiso)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permisos_usuarios (id_permiso INT NOT NULL, id_usuario INT NOT NULL, PRIMARY KEY(id_permiso, id_usuario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platillos (id_platillo INT AUTO_INCREMENT NOT NULL, id_categoria INT DEFAULT NULL, INDEX id_categoria (id_categoria), PRIMARY KEY(id_platillo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuarios (id_usuario INT AUTO_INCREMENT NOT NULL, usuario VARCHAR(190) NOT NULL, estado TINYINT(1) NOT NULL, nombre VARCHAR(190) NOT NULL, contrasena VARCHAR(190) NOT NULL, correo VARCHAR(190) NOT NULL, UNIQUE INDEX usuario (usuario), PRIMARY KEY(id_usuario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE acompanamientos');
        $this->addSql('DROP TABLE categorias');
        $this->addSql('DROP TABLE facturas');
        $this->addSql('DROP TABLE inventario');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_pedidos');
        $this->addSql('DROP TABLE mesa');
        $this->addSql('DROP TABLE mov_inventario');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('DROP TABLE permisos');
        $this->addSql('DROP TABLE permisos_usuarios');
        $this->addSql('DROP TABLE platillos');
        $this->addSql('DROP TABLE usuarios');
    }
}
