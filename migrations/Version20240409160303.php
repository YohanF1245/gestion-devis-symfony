<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409160303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE console_make_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business CHANGE user_id_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE reset_pass CHANGE user_id_id user_id_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_1483A5E95126AC48 ON users');
        $this->addSql('ALTER TABLE users ADD email VARCHAR(180) NOT NULL, DROP mail, DROP siret, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE pseudo pseudo VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE console_make_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE business CHANGE user_id_id user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reset_pass CHANGE user_id_id user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON users');
        $this->addSql('ALTER TABLE users ADD mail VARCHAR(128) NOT NULL, ADD siret VARCHAR(14) NOT NULL, DROP email, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE password password VARCHAR(128) NOT NULL, CHANGE pseudo pseudo VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E95126AC48 ON users (mail)');
    }
}
