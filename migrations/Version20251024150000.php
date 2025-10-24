<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251024150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Suppression des tables tag (si présentes) et pivots task_tag, project_tag';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        $platform = $this->connection->getDatabasePlatform()->getName();

        $dropIfExists = function (string $table) use ($sm) {
            if ($sm->tablesExist([$table])) {
                $this->addSql('DROP TABLE ' . $table);
            }
        };

        // Ordre: pivots puis table principale
        $dropIfExists('task_tag');
        $dropIfExists('project_tag');
        $dropIfExists('tag');
    }

    public function down(Schema $schema): void
    {
        // Restauration minimale (structure simplifiée)
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_tag (task_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_TASK_TAG_TASK (task_id), INDEX IDX_TASK_TAG_TAG (tag_id), PRIMARY KEY(task_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_tag (project_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_PROJECT_TAG_PROJECT (project_id), INDEX IDX_PROJECT_TAG_TAG (tag_id), PRIMARY KEY(project_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_TASK_TAG_TASK FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_TASK_TAG_TAG FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_PROJECT_TAG_PROJECT FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_PROJECT_TAG_TAG FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }
}
