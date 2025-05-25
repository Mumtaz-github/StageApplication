<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525225922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__utilisateurs AS SELECT id, nom, prenom, email, role, date_invitation FROM utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, date_invitation DATETIME NOT NULL, password VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO utilisateurs (id, nom, prenom, email, role, date_invitation) SELECT id, nom, prenom, email, role, date_invitation FROM __temp__utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_497B315EE7927C74 ON utilisateurs (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__utilisateurs AS SELECT id, nom, prenom, email, role, date_invitation FROM utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, date_invitation DATETIME NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO utilisateurs (id, nom, prenom, email, role, date_invitation) SELECT id, nom, prenom, email, role, date_invitation FROM __temp__utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__utilisateurs
        SQL);
    }
}
