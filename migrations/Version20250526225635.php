<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526225635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE formateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE formation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formateur_id INTEGER NOT NULL, actif_formation BOOLEAN NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, titre_professionnel VARCHAR(255) NOT NULL, niveau INTEGER NOT NULL, nb_stagiaires_previsionnel VARCHAR(255) NOT NULL, groupe_rattachement VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, CONSTRAINT FK_404021BF155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_404021BF155D8F51 ON formation (formateur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE interruption (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formation_id INTEGER NOT NULL, date_debut_int DATE NOT NULL, date_fin_int DATE NOT NULL, CONSTRAINT FK_F9511BC05200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F9511BC05200282E ON interruption (formation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, used BOOLEAN NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jour_ferie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, annee VARCHAR(4) NOT NULL, zone VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE period_en_entreprise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formation_id INTEGER NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, CONSTRAINT FK_92E223D25200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_92E223D25200282E ON period_en_entreprise (formation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, date_invitation DATETIME NOT NULL, password VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_497B315EE7927C74 ON utilisateurs (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE formateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interruption
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jour_ferie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE period_en_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
