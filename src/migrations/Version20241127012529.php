<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127012529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products CHANGE description description LONGTEXT NOT NULL, CHANGE details details LONGTEXT DEFAULT NULL, CHANGE images images LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE reviews CHANGE comment comment LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products CHANGE description description VARCHAR(255) NOT NULL, CHANGE details details VARCHAR(255) DEFAULT NULL, CHANGE images images VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reviews CHANGE comment comment VARCHAR(255) NOT NULL');
    }
}
