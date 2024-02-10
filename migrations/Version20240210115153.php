<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210115153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorias (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, descripcion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE datos_pedido (id INT AUTO_INCREMENT NOT NULL, unidades INT NOT NULL, pedido_id INT NOT NULL, producto_id INT DEFAULT NULL, INDEX IDX_3FCF0B374854653A (pedido_id), INDEX IDX_3FCF0B377645698E (producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE productos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, descripcion VARCHAR(255) NOT NULL, peso DOUBLE PRECISION NOT NULL, stock INT NOT NULL, categoria_id INT NOT NULL, INDEX IDX_767490E63397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE datos_pedido ADD CONSTRAINT FK_3FCF0B374854653A FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE datos_pedido ADD CONSTRAINT FK_3FCF0B377645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE productos ADD CONSTRAINT FK_767490E63397707A FOREIGN KEY (categoria_id) REFERENCES categorias (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE datos_pedido DROP FOREIGN KEY FK_3FCF0B374854653A');
        $this->addSql('ALTER TABLE datos_pedido DROP FOREIGN KEY FK_3FCF0B377645698E');
        $this->addSql('ALTER TABLE productos DROP FOREIGN KEY FK_767490E63397707A');
        $this->addSql('DROP TABLE categorias');
        $this->addSql('DROP TABLE datos_pedido');
        $this->addSql('DROP TABLE productos');
    }
}
