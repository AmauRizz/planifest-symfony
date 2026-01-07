<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522095149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP CONSTRAINT fk_3bae0aa72c7a725a
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE categorie_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_64C19C15E237E06 ON category (name)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN category.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN category.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_3bae0aa72c7a725a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event RENAME COLUMN categorie_entity_id TO category_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3BAE0AA712469DE2 ON event (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA712469DE2
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE categorie_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_497dd6345e237e06 ON categorie (name)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN categorie.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN categorie.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3BAE0AA712469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event RENAME COLUMN category_id TO categorie_entity_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT fk_3bae0aa72c7a725a FOREIGN KEY (categorie_entity_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_3bae0aa72c7a725a ON event (categorie_entity_id)
        SQL);
    }
}
