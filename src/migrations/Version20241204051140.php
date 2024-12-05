<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204051140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bills ADD canceled_at DATETIME DEFAULT NULL, ADD paid_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP canceled_at, DROP created_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bills DROP canceled_at, DROP paid_at');
        $this->addSql('ALTER TABLE orders ADD canceled_at DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL');
    }
}
