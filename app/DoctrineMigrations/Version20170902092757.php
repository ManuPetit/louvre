<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170902092757 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items ADD ticket_id INT DEFAULT NULL, ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D700047D2 ON items (ticket_id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D8D9F6D38 ON items (order_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D700047D2');
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D8D9F6D38');
        $this->addSql('DROP INDEX IDX_E11EE94D700047D2 ON items');
        $this->addSql('DROP INDEX IDX_E11EE94D8D9F6D38 ON items');
        $this->addSql('ALTER TABLE items DROP ticket_id, DROP order_id');
    }
}
