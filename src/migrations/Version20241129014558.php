<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129014558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bills (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, payMethod VARCHAR(255) NOT NULL, payment_service_provider VARCHAR(255) NOT NULL, total_price INT NOT NULL, order_id INT NOT NULL, UNIQUE INDEX UNIQ_22775DD08D9F6D38 (order_id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, parent_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, INDEX IDX_3AF34668139DF194 (promotion_id), INDEX IDX_3AF34668727ACA70 (parent_id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, products VARCHAR(255) NOT NULL, canceled_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, rate INT NOT NULL, slug VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL, images LONGTEXT DEFAULT NULL, category_id INT DEFAULT NULL, total_rates INT NOT NULL, total_reviews INT NOT NULL, promotion_id INT DEFAULT NULL, sold_number INT NOT NULL, is_deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B3BA5A5A139DF194 (promotion_id), INDEX IDX_B3BA5A5A12469DE2 (category_id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE promo_codes (id INT AUTO_INCREMENT NOT NULL, discount_percent INT NOT NULL, discount_amount INT NOT NULL, discount_max INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE promotions (id INT AUTO_INCREMENT NOT NULL, discount_percent INT DEFAULT NULL, discount_amount INT DEFAULT NULL, discount_max INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT NOT NULL, rate INT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, is_deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_claims (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, claim_name VARCHAR(255) NOT NULL, claim_value VARCHAR(255) NOT NULL, INDEX IDX_1585F20FD60322AC (role_id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_claims (id INT AUTO_INCREMENT NOT NULL, claim_type VARCHAR(255) NOT NULL, claim_value VARCHAR(255) NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_DBF10883A76ED395 (user_id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_logins (login_provider VARCHAR(255) NOT NULL, provider_key VARCHAR(255) NOT NULL, provider_display_name VARCHAR(255) NOT NULL, user_id INT NOT NULL, PRIMARY KEY(login_provider, provider_key))');
        $this->addSql('CREATE TABLE user_tokens (user_id INT NOT NULL, login_provider VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CF080AB3A76ED395 (user_id), PRIMARY KEY(user_id, login_provider))');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, roles VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, used_promo_codes VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE role_claims ADD CONSTRAINT FK_1585F20FD60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE user_claims ADD CONSTRAINT FK_DBF10883A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_tokens ADD CONSTRAINT FK_CF080AB3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD08D9F6D38');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668139DF194');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A139DF194');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE role_claims DROP FOREIGN KEY FK_1585F20FD60322AC');
        $this->addSql('ALTER TABLE user_claims DROP FOREIGN KEY FK_DBF10883A76ED395');
        $this->addSql('ALTER TABLE user_tokens DROP FOREIGN KEY FK_CF080AB3A76ED395');
        $this->addSql('DROP TABLE bills');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE promo_codes');
        $this->addSql('DROP TABLE promotions');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE role_claims');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE user_claims');
        $this->addSql('DROP TABLE user_logins');
        $this->addSql('DROP TABLE user_tokens');
        $this->addSql('DROP TABLE users');
    }
}
