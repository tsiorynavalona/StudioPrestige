<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218100951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos ADD id_photograph_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D966936FA2 FOREIGN KEY (id_photograph_id) REFERENCES photograph (id)');
        $this->addSql('CREATE INDEX IDX_876E0D966936FA2 ON photos (id_photograph_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D966936FA2');
        $this->addSql('DROP INDEX IDX_876E0D966936FA2 ON photos');
        $this->addSql('ALTER TABLE photos DROP id_photograph_id');
    }
}
