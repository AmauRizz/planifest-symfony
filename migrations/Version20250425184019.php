<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425184019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP capacity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP deleted_at
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_c53d045f989d9b62
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD data BYTEA NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD size INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP deleted_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image RENAME COLUMN slug TO name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP deleted_at
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP data
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP size
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image RENAME COLUMN name TO slug
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_c53d045f989d9b62 ON image (slug)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD slug VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD capacity INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN event.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
    }
}
