<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131153922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }


    public function up(Schema $schema): void
    {
        // Supprimer la contrainte de clé étrangère avant modification
        $this->addSql('ALTER TABLE applicant_job_offers DROP FOREIGN KEY FK_E5919C3C97139001');

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant ADD user_id INT NOT NULL, ADD last_name VARCHAR(128) NOT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD email VARCHAR(128) NOT NULL, ADD phone VARCHAR(128) NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE docs_to_provide docs_to_provide JSON NOT NULL');
        $this->addSql('ALTER TABLE applicant ADD CONSTRAINT FK_CAAD1019A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAAD1019A76ED395 ON applicant (user_id)');

        // Réappliquer la contrainte de clé étrangère après modification
        $this->addSql('ALTER TABLE applicant_job_offers ADD CONSTRAINT FK_E5919C3C97139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id) ON DELETE CASCADE');
    }


    public function down(Schema $schema): void
    {
        // Supprimer la contrainte avant d'annuler les modifications
        $this->addSql('ALTER TABLE applicant_job_offers DROP FOREIGN KEY FK_E5919C3C97139001');

        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant DROP FOREIGN KEY FK_CAAD1019A76ED395');
        $this->addSql('DROP INDEX UNIQ_CAAD1019A76ED395 ON applicant');
        $this->addSql('ALTER TABLE applicant DROP user_id, DROP last_name, DROP first_name, DROP email, DROP phone, CHANGE id id INT NOT NULL, CHANGE docs_to_provide docs_to_provide JSON DEFAULT NULL');

        // Réappliquer la contrainte d'origine
        $this->addSql('ALTER TABLE applicant_job_offers ADD CONSTRAINT FK_E5919C3C97139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id)');
    }
}
