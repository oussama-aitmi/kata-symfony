<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004114739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ligne_panier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE panier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE produit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ligne_panier (id INT NOT NULL, produit_id INT NOT NULL, panier_id INT DEFAULT NULL, quantite INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_21691B4F347EFB ON ligne_panier (produit_id)');
        $this->addSql('CREATE INDEX IDX_21691B4F77D927C ON ligne_panier (panier_id)');
        $this->addSql('CREATE TABLE panier (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE produit (id INT NOT NULL, code VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, category VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, internal_reference VARCHAR(100) DEFAULT NULL, shell_id INT NOT NULL, inventory_status VARCHAR(120) NOT NULL, rating DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN produit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN produit.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ligne_panier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE panier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE produit_id_seq CASCADE');
        $this->addSql('ALTER TABLE ligne_panier DROP CONSTRAINT FK_21691B4F347EFB');
        $this->addSql('ALTER TABLE ligne_panier DROP CONSTRAINT FK_21691B4F77D927C');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE produit');
    }
}
