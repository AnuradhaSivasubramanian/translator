<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200904101343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, domain_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain_translation_key (domain_id INT NOT NULL, translation_key_id INT NOT NULL, INDEX IDX_AAEBF6FD115F0EE5 (domain_id), INDEX IDX_AAEBF6FDD07ED992 (translation_key_id), PRIMARY KEY(domain_id, translation_key_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domain_translation_key ADD CONSTRAINT FK_AAEBF6FD115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domain_translation_key ADD CONSTRAINT FK_AAEBF6FDD07ED992 FOREIGN KEY (translation_key_id) REFERENCES translation_key (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_translation_key DROP FOREIGN KEY FK_AAEBF6FD115F0EE5');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE domain_translation_key');
    }
}
