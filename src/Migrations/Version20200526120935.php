<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526120935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE portfolio ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE upload_file DROP FOREIGN KEY FK_81BB169537A1329');
        $this->addSql('ALTER TABLE upload_file CHANGE file_name file_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

       // $this->addSql('ALTER TABLE portfolio DROP updated_at');
        $this->addSql('ALTER TABLE upload_file DROP FOREIGN KEY FK_81BB169537A1329');
        $this->addSql('ALTER TABLE upload_file CHANGE file_name file_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
