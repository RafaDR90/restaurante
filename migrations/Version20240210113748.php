<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240210113748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pedidos (id INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, enviado INT NOT NULL, restaurante_id INT NOT NULL, INDEX IDX_6716CCAA38B81E49 (restaurante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA38B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurante (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA38B81E49');
        $this->addSql('DROP TABLE pedidos');
    }
}
