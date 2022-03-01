<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228113759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO song (name) VALUES ('Dove Cameron - Boyfriend')");
        $this->addSql("INSERT INTO song (name) VALUES ('Sabrina Carpenter - Fast Times')");
        $this->addSql("INSERT INTO song (name) VALUES ('Jamie Miller - I Lost Myself In Loving You')");
        $this->addSql("INSERT INTO song (name) VALUES ('Elton John, Dua Lipa - Cold Heart')");
        $this->addSql("INSERT INTO song (name) VALUES ('Ed Sheeran - The Joker And The Queen')");
        $this->addSql("INSERT INTO song (name) VALUES ('Tate McRae - she\'s all i wanna be')");
        $this->addSql("INSERT INTO song (name) VALUES ('Coldplay X Selena Gomez - Let Somebody Go')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
