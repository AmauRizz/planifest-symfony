<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522090331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD author_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3BAE0AA7F675F31B ON event (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD author_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045FF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045FF675F31B ON image (author_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3BAE0AA7F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP author_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP CONSTRAINT FK_C53D045FF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C53D045FF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP author_id
        SQL);
    }
}
