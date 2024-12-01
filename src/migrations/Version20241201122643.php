<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201122643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_logins DROP provider_display_name');
        $this->addSql('ALTER TABLE user_logins ADD CONSTRAINT FK_6341CC99A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6341CC99A76ED395 ON user_logins (user_id)');
        $this->addSql('ALTER TABLE users ADD is_verified_email TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP is_verified_email');
        $this->addSql('ALTER TABLE user_logins DROP FOREIGN KEY FK_6341CC99A76ED395');
        $this->addSql('DROP INDEX UNIQ_6341CC99A76ED395 ON user_logins');
        $this->addSql('ALTER TABLE user_logins ADD provider_display_name VARCHAR(255) NOT NULL');
    }
}
