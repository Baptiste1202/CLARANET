<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113151726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicules ADD created_by_id INT NOT NULL, DROP created_by');
        $this->addSql('ALTER TABLE vehicules ADD CONSTRAINT FK_78218C2DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_78218C2DB03A8386 ON vehicules (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicules DROP FOREIGN KEY FK_78218C2DB03A8386');
        $this->addSql('DROP INDEX IDX_78218C2DB03A8386 ON vehicules');
        $this->addSql('ALTER TABLE vehicules ADD created_by VARCHAR(255) DEFAULT NULL, DROP created_by_id');
    }
}
