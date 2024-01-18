<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231229133929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_photos_photos (categories_photos_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_9E16D7F55CD29AC3 (categories_photos_id), INDEX IDX_9E16D7F5301EC62 (photos_id), PRIMARY KEY(categories_photos_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_photos_photos ADD CONSTRAINT FK_9E16D7F55CD29AC3 FOREIGN KEY (categories_photos_id) REFERENCES categories_photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_photos_photos ADD CONSTRAINT FK_9E16D7F5301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_categories_photos DROP FOREIGN KEY FK_84EB3089301EC62');
        $this->addSql('ALTER TABLE photos_categories_photos DROP FOREIGN KEY FK_84EB30895CD29AC3');
        $this->addSql('DROP TABLE photos_categories_photos');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photos_categories_photos (photos_id INT NOT NULL, categories_photos_id INT NOT NULL, INDEX IDX_84EB3089301EC62 (photos_id), INDEX IDX_84EB30895CD29AC3 (categories_photos_id), PRIMARY KEY(photos_id, categories_photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE photos_categories_photos ADD CONSTRAINT FK_84EB3089301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_categories_photos ADD CONSTRAINT FK_84EB30895CD29AC3 FOREIGN KEY (categories_photos_id) REFERENCES categories_photos (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_photos_photos DROP FOREIGN KEY FK_9E16D7F55CD29AC3');
        $this->addSql('ALTER TABLE categories_photos_photos DROP FOREIGN KEY FK_9E16D7F5301EC62');
        $this->addSql('DROP TABLE categories_photos_photos');
    }
}
