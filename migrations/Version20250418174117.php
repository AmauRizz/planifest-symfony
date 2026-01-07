<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418174117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD categorie_entity_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA72C7A725A FOREIGN KEY (categorie_entity_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3BAE0AA72C7A725A ON event (categorie_entity_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD user_entity_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD event_entity_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F81C5F0B9 FOREIGN KEY (user_entity_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F7B04360 FOREIGN KEY (event_entity_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_C53D045F81C5F0B9 ON image (user_entity_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045F7B04360 ON image (event_entity_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD role_entity_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649D0D1AE81 FOREIGN KEY (role_entity_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649D0D1AE81 ON "user" (role_entity_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_user DROP CONSTRAINT FK_92589AE271F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_user DROP CONSTRAINT FK_92589AE2A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649D0D1AE81
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649D0D1AE81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP role_entity_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA72C7A725A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3BAE0AA72C7A725A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP categorie_entity_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP CONSTRAINT FK_C53D045F81C5F0B9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP CONSTRAINT FK_C53D045F7B04360
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_C53D045F81C5F0B9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C53D045F7B04360
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP user_entity_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP event_entity_id
        SQL);
    }
}
