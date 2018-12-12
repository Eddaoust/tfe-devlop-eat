<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181212213146 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project ADD architect_id INT DEFAULT NULL, ADD general_company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9F7266C0 FOREIGN KEY (architect_id) REFERENCES architect (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEE4A43847 FOREIGN KEY (general_company_id) REFERENCES general_company (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9F7266C0 ON project (architect_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEE4A43847 ON project (general_company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE9F7266C0');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEE4A43847');
        $this->addSql('DROP INDEX IDX_2FB3D0EE9F7266C0 ON project');
        $this->addSql('DROP INDEX IDX_2FB3D0EEE4A43847 ON project');
        $this->addSql('ALTER TABLE project DROP architect_id, DROP general_company_id');
    }
}
