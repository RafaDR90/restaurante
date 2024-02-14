<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214192926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //hago un insert en categorias de la categoria "sin categoria"
        $this->addSql('INSERT INTO categorias (nombre, descripcion) VALUES ("Sin categoría", "Categoría predeterminada para productos sin categoría asignada.")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM categorias WHERE nombre = "Sin categoría"');
    }
}
