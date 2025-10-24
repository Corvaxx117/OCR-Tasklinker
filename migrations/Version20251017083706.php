<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017083706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5D9F75A1E7927C74 ON employee');
        $this->addSql('ALTER TABLE employee ADD started_at DATETIME NOT NULL, ADD access_status VARCHAR(255) NOT NULL, ADD contract_type VARCHAR(255) NOT NULL, ADD password_hash VARCHAR(255) NOT NULL, DROP entry_date, DROP status, CHANGE last_name last_name VARCHAR(100) NOT NULL, CHANGE first_name first_name VARCHAR(100) NOT NULL, CHANGE email mail VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A15126AC48 ON employee (mail)');
        $this->addSql('ALTER TABLE project ADD name VARCHAR(100) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD started_at DATETIME NOT NULL, ADD deadline DATETIME DEFAULT NULL, DROP title, CHANGE archived is_archived TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5D9F75A15126AC48 ON employee');
        $this->addSql('ALTER TABLE employee ADD email VARCHAR(255) NOT NULL, ADD entry_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD status VARCHAR(50) NOT NULL, DROP mail, DROP started_at, DROP access_status, DROP contract_type, DROP password_hash, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1E7927C74 ON employee (email)');
        $this->addSql('ALTER TABLE project ADD title VARCHAR(255) NOT NULL, DROP name, DROP description, DROP started_at, DROP deadline, CHANGE is_archived archived TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
