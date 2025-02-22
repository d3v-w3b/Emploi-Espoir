<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221123313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career ADD github_url VARCHAR(255) DEFAULT NULL AFTER linked_in_url, ADD website_url VARCHAR(255) DEFAULT NULL AFTER github_url, CHANGE external_link linked_in_url VARCHAR(255) DEFAULT NULL AFTER skills');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career ADD external_link VARCHAR(255) DEFAULT NULL, DROP linked_in_url, DROP github_url, DROP website_url');
    }
}
