<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116133230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_photos CHANGE descriptons descriptions LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE categories_photos ADD CONSTRAINT FK_1FDEE13DA5256D FOREIGN KEY (image_id) REFERENCES photos (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FDEE13DA5256D ON categories_photos (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_photos DROP FOREIGN KEY FK_1FDEE13DA5256D');
        $this->addSql('DROP INDEX UNIQ_1FDEE13DA5256D ON categories_photos');
        $this->addSql('ALTER TABLE categories_photos CHANGE descriptions descriptons LONGTEXT NOT NULL');
    }
}
