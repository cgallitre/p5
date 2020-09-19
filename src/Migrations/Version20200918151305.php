<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918151305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE type ADD screen_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE theme ADD screen_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE portfolio ADD published TINYINT(1) DEFAULT NULL, ADD screen_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD published TINYINT(1) DEFAULT NULL, ADD screen_order INT DEFAULT NULL, ADD meta_desc LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE portfolio DROP published, DROP screen_order');
        $this->addSql('ALTER TABLE theme DROP screen_order');
        $this->addSql('ALTER TABLE training DROP published, DROP screen_order, DROP meta_desc');
        $this->addSql('ALTER TABLE type DROP screen_order');
    }
}
