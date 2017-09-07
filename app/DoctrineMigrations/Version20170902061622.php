<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170902061622 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE items (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, first_name VARCHAR(45) NOT NULL, last_name VARCHAR(45) NOT NULL, birth_date DATE NOT NULL, INDEX IDX_E11EE94DF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, duration_id INT DEFAULT NULL, order_date DATETIME NOT NULL, venue_date DATE NOT NULL, order_number VARCHAR(20) NOT NULL, customer_email VARCHAR(100) NOT NULL, order_paid TINYINT(1) NOT NULL, INDEX IDX_E52FFDEE37B987D8 (duration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94DF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE37B987D8 FOREIGN KEY (duration_id) REFERENCES durations (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE orders');
    }
}
