<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200817115432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation_message ADD translation_key_id INT DEFAULT NULL, DROP text_key_id');
        $this->addSql('ALTER TABLE translation_message ADD CONSTRAINT FK_17B1C98D6E75D05C FOREIGN KEY (translation_key_id) REFERENCES translation_key (id)');
        $this->addSql('CREATE INDEX IDX_17B1C98D6E75D05C ON translation_message (translation_key_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation_message DROP FOREIGN KEY FK_17B1C98D6E75D05C');
        $this->addSql('DROP INDEX IDX_17B1C98D6E75D05C ON translation_message');
        $this->addSql('ALTER TABLE translation_message ADD text_key_id INT NOT NULL, DROP translation_key_id');
    }
}