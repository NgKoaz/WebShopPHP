<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209085906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mail_service_manager (email VARCHAR(255) NOT NULL, sent_verify_email_at DATETIME NOT NULL, PRIMARY KEY(email))');
        $this->addSql('ALTER TABLE users ADD can_reviews LONGTEXT DEFAULT NULL, CHANGE address address LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mail_service_manager');
        $this->addSql('ALTER TABLE users DROP can_reviews, CHANGE address address VARCHAR(255) DEFAULT NULL');
    }
}
