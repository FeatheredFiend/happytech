<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329130222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_log (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, action VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE applicant ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE feedback_response ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE feedback_response_statement ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE feedback_type ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE job ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE job_applicant ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE job_category ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE statement ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE template ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE template_header ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE template_statement ADD decommissioned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD decommissioned TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE action_log');
        $this->addSql('ALTER TABLE applicant DROP decommissioned');
        $this->addSql('ALTER TABLE feedback_response DROP decommissioned');
        $this->addSql('ALTER TABLE feedback_response_statement DROP decommissioned');
        $this->addSql('ALTER TABLE feedback_type DROP decommissioned');
        $this->addSql('ALTER TABLE job DROP decommissioned');
        $this->addSql('ALTER TABLE job_applicant DROP decommissioned');
        $this->addSql('ALTER TABLE job_category DROP decommissioned');
        $this->addSql('ALTER TABLE statement DROP decommissioned');
        $this->addSql('ALTER TABLE template DROP decommissioned');
        $this->addSql('ALTER TABLE template_header DROP decommissioned');
        $this->addSql('ALTER TABLE template_statement DROP decommissioned');
        $this->addSql('ALTER TABLE user DROP decommissioned');
    }
}
