<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221172801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career DROP job_title, DROP job_field, DROP town, DROP enterprise_name, DROP start_date, DROP end_date, DROP job_description');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career ADD job_title VARCHAR(255) DEFAULT NULL, ADD job_field JSON DEFAULT NULL, ADD town VARCHAR(128) DEFAULT NULL, ADD enterprise_name VARCHAR(128) DEFAULT NULL, ADD start_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD job_description LONGTEXT DEFAULT NULL');
    }
}
