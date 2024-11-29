<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129132950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE career (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, about_you LONGTEXT DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, skills JSON DEFAULT NULL, external_link VARCHAR(255) DEFAULT NULL, job_title VARCHAR(255) DEFAULT NULL, job_field JSON DEFAULT NULL, country VARCHAR(128) DEFAULT NULL, enterprise_name VARCHAR(128) DEFAULT NULL, start_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', job_description LONGTEXT DEFAULT NULL, french_level VARCHAR(128) DEFAULT NULL, english_level VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_B25B6C84A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE career ADD CONSTRAINT FK_B25B6C84A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career DROP FOREIGN KEY FK_B25B6C84A76ED395');
        $this->addSql('DROP TABLE career');
    }
}
