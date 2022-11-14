<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113183924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_data ADD CONSTRAINT FK_698B60E6A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('CREATE INDEX IDX_698B60E6A0BDB2F3 ON rating_data (song_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_data DROP FOREIGN KEY FK_698B60E6A0BDB2F3');
        $this->addSql('DROP INDEX IDX_698B60E6A0BDB2F3 ON rating_data');
        $this->addSql('ALTER TABLE rating_data CHANGE customer_name customer_name VARCHAR(256) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE customer_email customer_email VARCHAR(256) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE song CHANGE name name VARCHAR(256) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
