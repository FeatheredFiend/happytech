<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401123042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE table_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action_log ADD tablename_id INT NOT NULL, ADD rownumber INT NOT NULL');
        $this->addSql('ALTER TABLE action_log ADD CONSTRAINT FK_B2C5F685D6F67E96 FOREIGN KEY (tablename_id) REFERENCES table_list (id)');
        $this->addSql('CREATE INDEX IDX_B2C5F685D6F67E96 ON action_log (tablename_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action_log DROP FOREIGN KEY FK_B2C5F685D6F67E96');
        $this->addSql('DROP TABLE table_list');
        $this->addSql('DROP INDEX IDX_B2C5F685D6F67E96 ON action_log');
        $this->addSql('ALTER TABLE action_log DROP tablename_id, DROP rownumber');
    }
}
