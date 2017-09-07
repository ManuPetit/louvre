<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170901152743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, short_code VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE durations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rates (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, duration_id INT DEFAULT NULL, rate_id INT DEFAULT NULL, price NUMERIC(7, 2) NOT NULL, INDEX IDX_54469DF437B987D8 (duration_id), INDEX IDX_54469DF4BC999F9F (rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF437B987D8 FOREIGN KEY (duration_id) REFERENCES durations (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4BC999F9F FOREIGN KEY (rate_id) REFERENCES rates (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF437B987D8');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4BC999F9F');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE durations');
        $this->addSql('DROP TABLE rates');
        $this->addSql('DROP TABLE tickets');
    }
}
