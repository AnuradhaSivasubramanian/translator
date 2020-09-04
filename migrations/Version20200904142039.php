<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200904142039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_translation_key DROP FOREIGN KEY FK_AAEBF6FD115F0EE5');
        $this->addSql('ALTER TABLE domain_translation_key DROP FOREIGN KEY FK_AAEBF6FDD07ED992');
        $this->addSql('ALTER TABLE domain_translation_key ADD CONSTRAINT FK_AAEBF6FD115F0EE5 FOREIGN KEY (domain_id) REFERENCES translation_key (id)');
        $this->addSql('ALTER TABLE domain_translation_key ADD CONSTRAINT FK_AAEBF6FDD07ED992 FOREIGN KEY (translation_key_id) REFERENCES domain (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_translation_key DROP FOREIGN KEY FK_AAEBF6FD115F0EE5');
        $this->addSql('ALTER TABLE domain_translation_key DROP FOREIGN KEY FK_AAEBF6FDD07ED992');
        $this->addSql('ALTER TABLE domain_translation_key ADD CONSTRAINT FK_AAEBF6FD115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domain_translation_key ADD CONSTRAINT FK_AAEBF6FDD07ED992 FOREIGN KEY (translation_key_id) REFERENCES translation_key (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
