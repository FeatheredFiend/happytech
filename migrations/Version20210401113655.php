<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401113655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497CB6A05E237E06 ON feedback_type (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_610BBCBA5E237E06 ON job_category (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0DB5176C0DB5176 ON statement (statement)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F835E237E06 ON template (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_659AA1335E237E06 ON template_header (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_497CB6A05E237E06 ON feedback_type');
        $this->addSql('DROP INDEX UNIQ_610BBCBA5E237E06 ON job_category');
        $this->addSql('DROP INDEX UNIQ_C0DB5176C0DB5176 ON statement');
        $this->addSql('DROP INDEX UNIQ_97601F835E237E06 ON template');
        $this->addSql('DROP INDEX UNIQ_659AA1335E237E06 ON template_header');
    }
}
