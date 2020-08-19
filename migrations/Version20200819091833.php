<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819091833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation_message DROP FOREIGN KEY FK_17B1C98D6E75D05C');
        $this->addSql('DROP INDEX IDX_17B1C98D6E75D05C ON translation_message');
        $this->addSql('ALTER TABLE translation_message CHANGE translation_key_id translation_key_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation_message CHANGE translation_key_id translation_key_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE translation_message ADD CONSTRAINT FK_17B1C98D6E75D05C FOREIGN KEY (translation_key_id) REFERENCES translation_key (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_17B1C98D6E75D05C ON translation_message (translation_key_id)');
    }
}
