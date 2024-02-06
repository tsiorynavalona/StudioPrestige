<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205154350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, time_start TIME NOT NULL, time_end TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_photos ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories_photos ADD CONSTRAINT FK_1FDEE1B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_1FDEE1B83297E7 ON categories_photos (reservation_id)');
        $this->addSql('ALTER TABLE photograph ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photograph ADD CONSTRAINT FK_D551C733B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_D551C733B83297E7 ON photograph (reservation_id)');
        $this->addSql('ALTER TABLE user ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B83297E7 ON user (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_photos DROP FOREIGN KEY FK_1FDEE1B83297E7');
        $this->addSql('ALTER TABLE photograph DROP FOREIGN KEY FK_D551C733B83297E7');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B83297E7');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP INDEX IDX_1FDEE1B83297E7 ON categories_photos');
        $this->addSql('ALTER TABLE categories_photos DROP reservation_id');
        $this->addSql('DROP INDEX IDX_D551C733B83297E7 ON photograph');
        $this->addSql('ALTER TABLE photograph DROP reservation_id');
        $this->addSql('DROP INDEX IDX_8D93D649B83297E7 ON user');
        $this->addSql('ALTER TABLE user DROP reservation_id');
    }
}
