<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228110219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE rating_data (id INT AUTO_INCREMENT NOT NULL, customer_name VARCHAR(256) NOT NULL, customer_email VARCHAR(100) NOT NULL, rating VARCHAR(10), song_id INT, PRIMARY KEY(id), UNIQUE(customer_email)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE rating_data');
    }
}
