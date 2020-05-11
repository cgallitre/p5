<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510092441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE upload_file (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, upload_at DATETIME NOT NULL, INDEX IDX_81BB169537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE role_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE role_user ADD PRIMARY KEY (user_id, role_id)');
        $this->addSql('ALTER TABLE project_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project_user ADD PRIMARY KEY (user_id, project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE upload_file');
        $this->addSql('ALTER TABLE project_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project_user ADD PRIMARY KEY (project_id, user_id)');
        $this->addSql('ALTER TABLE role_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE role_user ADD PRIMARY KEY (role_id, user_id)');
    }
}
