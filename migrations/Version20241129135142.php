<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129135142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, diploma_level VARCHAR(128) DEFAULT NULL, diploma_name VARCHAR(128) DEFAULT NULL, diploma_specialities JSON DEFAULT NULL, university_name VARCHAR(128) DEFAULT NULL, diploma_town VARCHAR(128) DEFAULT NULL, diploma_month DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', diploma_year DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', diploma JSON DEFAULT NULL, UNIQUE INDEX UNIQ_404021BFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFA76ED395');
        $this->addSql('DROP TABLE formation');
    }
}
