<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410124443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', siret VARCHAR(14) NOT NULL, logo VARCHAR(255) DEFAULT NULL, INDEX IDX_8D36E389D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, business_name VARCHAR(255) DEFAULT NULL, is_physick TINYINT(1) NOT NULL, mail VARCHAR(255) DEFAULT NULL, num_street VARCHAR(64) NOT NULL, street VARCHAR(255) NOT NULL, zip_postal VARCHAR(128) NOT NULL, index_tel VARCHAR(10) DEFAULT NULL, phone_number VARCHAR(14) DEFAULT NULL, town VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dress_estimate (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', creation_date DATETIME NOT NULL, estimate_number INT NOT NULL, validity INT DEFAULT NULL, expiration_date DATETIME NOT NULL, intitule VARCHAR(128) NOT NULL, free_zone LONGTEXT DEFAULT NULL, accompte INT DEFAULT NULL, discount INT DEFAULT NULL, INDEX IDX_4E0EB682DC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimate_tab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', business_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', estimate_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', facture_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_1B043BA31A579E8 (business_id_id), UNIQUE INDEX UNIQ_1B043BA3805AF98 (estimate_id_id), UNIQUE INDEX UNIQ_1B043BA3ED7016E0 (facture_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimate_tab_performance (estimate_tab_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', performance_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_FF37F21986A00D33 (estimate_tab_id), INDEX IDX_FF37F219B91ADEEE (performance_id), PRIMARY KEY(estimate_tab_id, performance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_emit (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', creation_date DATETIME NOT NULL, payment_date DATETIME DEFAULT NULL, majoration INT DEFAULT NULL, date_limit DATETIME DEFAULT NULL, is_paid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outcome (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', business_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', outcome_date DATETIME NOT NULL, outcome_amount INT NOT NULL, recipe_link VARCHAR(255) DEFAULT NULL, INDEX IDX_30BC6DC21A579E8 (business_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE performance (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', quantity INT NOT NULL, designation VARCHAR(255) NOT NULL, tax DOUBLE PRECISION DEFAULT NULL, pirce INT NOT NULL, unit VARCHAR(64) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_pass (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reset_date DATETIME NOT NULL, reset_link VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_395710FC9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, signature VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E389D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE dress_estimate ADD CONSTRAINT FK_4E0EB682DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE estimate_tab ADD CONSTRAINT FK_1B043BA31A579E8 FOREIGN KEY (business_id_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE estimate_tab ADD CONSTRAINT FK_1B043BA3805AF98 FOREIGN KEY (estimate_id_id) REFERENCES dress_estimate (id)');
        $this->addSql('ALTER TABLE estimate_tab ADD CONSTRAINT FK_1B043BA3ED7016E0 FOREIGN KEY (facture_id_id) REFERENCES facture_emit (id)');
        $this->addSql('ALTER TABLE estimate_tab_performance ADD CONSTRAINT FK_FF37F21986A00D33 FOREIGN KEY (estimate_tab_id) REFERENCES estimate_tab (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE estimate_tab_performance ADD CONSTRAINT FK_FF37F219B91ADEEE FOREIGN KEY (performance_id) REFERENCES performance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE outcome ADD CONSTRAINT FK_30BC6DC21A579E8 FOREIGN KEY (business_id_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE reset_pass ADD CONSTRAINT FK_395710FC9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E389D86650F');
        $this->addSql('ALTER TABLE dress_estimate DROP FOREIGN KEY FK_4E0EB682DC2902E0');
        $this->addSql('ALTER TABLE estimate_tab DROP FOREIGN KEY FK_1B043BA31A579E8');
        $this->addSql('ALTER TABLE estimate_tab DROP FOREIGN KEY FK_1B043BA3805AF98');
        $this->addSql('ALTER TABLE estimate_tab DROP FOREIGN KEY FK_1B043BA3ED7016E0');
        $this->addSql('ALTER TABLE estimate_tab_performance DROP FOREIGN KEY FK_FF37F21986A00D33');
        $this->addSql('ALTER TABLE estimate_tab_performance DROP FOREIGN KEY FK_FF37F219B91ADEEE');
        $this->addSql('ALTER TABLE outcome DROP FOREIGN KEY FK_30BC6DC21A579E8');
        $this->addSql('ALTER TABLE reset_pass DROP FOREIGN KEY FK_395710FC9D86650F');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE dress_estimate');
        $this->addSql('DROP TABLE estimate_tab');
        $this->addSql('DROP TABLE estimate_tab_performance');
        $this->addSql('DROP TABLE facture_emit');
        $this->addSql('DROP TABLE outcome');
        $this->addSql('DROP TABLE performance');
        $this->addSql('DROP TABLE reset_pass');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
