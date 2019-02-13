<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212150157 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, study DATETIME DEFAULT NULL, mastery DATETIME DEFAULT NULL, permit_start DATETIME DEFAULT NULL, permit_end DATETIME DEFAULT NULL, works_start DATETIME DEFAULT NULL, works_end DATETIME DEFAULT NULL, delivery DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD steps_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE1EBBD054 FOREIGN KEY (steps_id) REFERENCES step (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE1EBBD054 ON project (steps_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE1EBBD054');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE1EBBD054 ON project');
        $this->addSql('ALTER TABLE project DROP steps_id');
    }
}
