<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration enum TaskStatus: ajoute colonne status, copie données depuis project_status.label puis supprime la contrainte.
 */
final class Version20251024140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Task.status enum and migrate data from project_status relation';
    }

    public function up(Schema $schema): void
    {
        // 1. Ajouter colonne status (nullable temporairement pour migration)
        $this->addSql("ALTER TABLE task ADD status VARCHAR(10) DEFAULT NULL");
        // 2. Peupler la colonne status en fonction du label associé
        // Mapping simple: 'Doing' => DOING, 'Done' => DONE, else TODO
        $this->addSql("UPDATE task t JOIN project_status ps ON t.project_status_id = ps.id SET t.status = 'DOING' WHERE ps.label = 'Doing'");
        $this->addSql("UPDATE task t JOIN project_status ps ON t.project_status_id = ps.id SET t.status = 'DONE' WHERE ps.label = 'Done'");
        $this->addSql("UPDATE task t JOIN project_status ps ON t.project_status_id = ps.id SET t.status = 'TODO' WHERE t.status IS NULL");
        // 3. Rendre non null + default
        $this->addSql("ALTER TABLE task CHANGE status status VARCHAR(10) NOT NULL");
        // 4. (Optionnel maintenant) supprimer FK et colonne project_status_id + table project_status
        $this->addSql("ALTER TABLE task DROP FOREIGN KEY FK_527EDB257ACB456A");
        $this->addSql("ALTER TABLE task DROP COLUMN project_status_id");
        $this->addSql("DROP TABLE project_status");
    }

    public function down(Schema $schema): void
    {
        // Recréation table project_status
        $this->addSql('CREATE TABLE project_status (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_6CA48E56166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_status ADD CONSTRAINT FK_6CA48E56166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE task ADD project_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257ACB456A FOREIGN KEY (project_status_id) REFERENCES project_status (id)');
        $this->addSql('CREATE INDEX IDX_527EDB257ACB456A ON task (project_status_id)');
        // Remettre une valeur par défaut: toutes en TODO label synthétique
        $this->addSql("INSERT INTO project_status (project_id, label) SELECT DISTINCT project_id, 'To Do' FROM task");
        $this->addSql("UPDATE task t JOIN project_status ps ON ps.project_id = t.project_id AND ps.label='To Do' SET t.project_status_id = ps.id");
        $this->addSql('ALTER TABLE task DROP COLUMN status');
    }
}
