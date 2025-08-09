<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250809011640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hiring ADD applicant_id INT NOT NULL, ADD organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE hiring ADD CONSTRAINT FK_6FDF13C297139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id)');
        $this->addSql('ALTER TABLE hiring ADD CONSTRAINT FK_6FDF13C232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_6FDF13C297139001 ON hiring (applicant_id)');
        $this->addSql('CREATE INDEX IDX_6FDF13C232C8A3DE ON hiring (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hiring DROP FOREIGN KEY FK_6FDF13C297139001');
        $this->addSql('ALTER TABLE hiring DROP FOREIGN KEY FK_6FDF13C232C8A3DE');
        $this->addSql('DROP INDEX IDX_6FDF13C297139001 ON hiring');
        $this->addSql('DROP INDEX IDX_6FDF13C232C8A3DE ON hiring');
        $this->addSql('ALTER TABLE hiring DROP applicant_id, DROP organization_id');
    }
}
