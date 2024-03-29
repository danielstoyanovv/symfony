<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119085713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD api_token_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64992E52D36 FOREIGN KEY (api_token_id) REFERENCES api_token (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992E52D36 ON user (api_token_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64992E52D36');
        $this->addSql('DROP INDEX UNIQ_8D93D64992E52D36 ON user');
        $this->addSql('ALTER TABLE user DROP api_token_id');
    }
}
