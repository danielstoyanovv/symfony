<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128150812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE author author VARCHAR(255) DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song DROP created_at, DROP updated_at, CHANGE name name VARCHAR(256) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE author author VARCHAR(256) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(256) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
