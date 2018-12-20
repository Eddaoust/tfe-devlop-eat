<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181220120613 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shareholder ADD shareholder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shareholder ADD CONSTRAINT FK_D5FE68CC9D59475 FOREIGN KEY (shareholder_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_D5FE68CC9D59475 ON shareholder (shareholder_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shareholder DROP FOREIGN KEY FK_D5FE68CC9D59475');
        $this->addSql('DROP INDEX IDX_D5FE68CC9D59475 ON shareholder');
        $this->addSql('ALTER TABLE shareholder DROP shareholder_id');
    }
}
