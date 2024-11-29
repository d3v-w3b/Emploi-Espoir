<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129114733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_and_alternation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, alternation_zone VARCHAR(128) DEFAULT NULL, alternation_preference JSON DEFAULT NULL, alternation_field VARCHAR(128) DEFAULT NULL, employment_area VARCHAR(128) DEFAULT NULL, employment_preference JSON DEFAULT NULL, UNIQUE INDEX UNIQ_69C3E119A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_and_alternation ADD CONSTRAINT FK_69C3E119A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_and_alternation DROP FOREIGN KEY FK_69C3E119A76ED395');
        $this->addSql('DROP TABLE job_and_alternation');
    }
}
