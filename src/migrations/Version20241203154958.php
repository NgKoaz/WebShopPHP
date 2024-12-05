<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203154958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bills ADD user_id INT NOT NULL, CHANGE id id VARCHAR(255) NOT NULL, CHANGE payMethod payMethod VARCHAR(255) DEFAULT NULL, CHANGE payment_service_provider payment_service_provider VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders CHANGE products products LONGTEXT NOT NULL, CHANGE canceled_at canceled_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bills DROP user_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE payMethod payMethod VARCHAR(255) NOT NULL, CHANGE payment_service_provider payment_service_provider VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders CHANGE products products VARCHAR(255) NOT NULL, CHANGE canceled_at canceled_at DATETIME NOT NULL');
    }
}
