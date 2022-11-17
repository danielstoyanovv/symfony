<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117125441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_data ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rating_data ADD CONSTRAINT FK_698B60E6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_698B60E6A76ED395 ON rating_data (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_data DROP FOREIGN KEY FK_698B60E6A76ED395');
        $this->addSql('DROP INDEX IDX_698B60E6A76ED395 ON rating_data');
        $this->addSql('ALTER TABLE rating_data DROP user_id');
    }
}
