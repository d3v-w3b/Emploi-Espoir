<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115133443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_offers (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, job_title VARCHAR(128) DEFAULT NULL, type_of_contract VARCHAR(128) DEFAULT NULL, town VARCHAR(128) DEFAULT NULL, job_preferences VARCHAR(128) DEFAULT NULL, organization_about LONGTEXT DEFAULT NULL, missions JSON DEFAULT NULL, profil_sought JSON DEFAULT NULL, what_we_offer JSON DEFAULT NULL, docs_to_provide JSON DEFAULT NULL, date_of_publication DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', expiration_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8A4229A632C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_offers ADD CONSTRAINT FK_8A4229A632C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offers DROP FOREIGN KEY FK_8A4229A632C8A3DE');
        $this->addSql('DROP TABLE job_offers');
    }
}
