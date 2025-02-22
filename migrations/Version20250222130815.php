<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222130815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experiences (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, job_title VARCHAR(128) DEFAULT NULL, job_field JSON DEFAULT NULL, enterprise_name VARCHAR(128) DEFAULT NULL, start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', job_description LONGTEXT DEFAULT NULL, INDEX IDX_82020E70A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E70A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E70A76ED395');
        $this->addSql('DROP TABLE experiences');
    }
}
