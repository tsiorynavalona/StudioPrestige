<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218095602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_photos (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_photos_photos (categories_photos_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_9E16D7F55CD29AC3 (categories_photos_id), INDEX IDX_9E16D7F5301EC62 (photos_id), PRIMARY KEY(categories_photos_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_photos_photos ADD CONSTRAINT FK_9E16D7F55CD29AC3 FOREIGN KEY (categories_photos_id) REFERENCES categories_photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_photos_photos ADD CONSTRAINT FK_9E16D7F5301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_photos_photos DROP FOREIGN KEY FK_9E16D7F55CD29AC3');
        $this->addSql('ALTER TABLE categories_photos_photos DROP FOREIGN KEY FK_9E16D7F5301EC62');
        $this->addSql('DROP TABLE categories_photos');
        $this->addSql('DROP TABLE categories_photos_photos');
    }
}
