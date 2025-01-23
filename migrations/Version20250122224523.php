<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250122224523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applicant (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(128) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(128) NOT NULL, phone VARCHAR(128) NOT NULL, docs_to_provide JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicant_job_offers (applicant_id INT NOT NULL, job_offers_id INT NOT NULL, INDEX IDX_E5919C3C97139001 (applicant_id), INDEX IDX_E5919C3C67205B3F (job_offers_id), PRIMARY KEY(applicant_id, job_offers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE applicant_job_offers ADD CONSTRAINT FK_E5919C3C97139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE applicant_job_offers ADD CONSTRAINT FK_E5919C3C67205B3F FOREIGN KEY (job_offers_id) REFERENCES job_offers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant_job_offers DROP FOREIGN KEY FK_E5919C3C97139001');
        $this->addSql('ALTER TABLE applicant_job_offers DROP FOREIGN KEY FK_E5919C3C67205B3F');
        $this->addSql('DROP TABLE applicant');
        $this->addSql('DROP TABLE applicant_job_offers');
    }
}
