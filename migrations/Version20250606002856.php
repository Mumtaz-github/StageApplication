<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606002856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__jour_ferie AS SELECT id, date, zone, nom FROM jour_ferie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jour_ferie
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jour_ferie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, zone VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO jour_ferie (id, date, zone, nom) SELECT id, date, zone, nom FROM __temp__jour_ferie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__jour_ferie
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE jour_ferie ADD COLUMN annee VARCHAR(4) NOT NULL
        SQL);
    }
}
