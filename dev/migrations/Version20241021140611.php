<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021140611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicule_optionnel (vehicule_id INT NOT NULL, optionnel_id INT NOT NULL, INDEX IDX_8CF3FE4D4A4A3511 (vehicule_id), INDEX IDX_8CF3FE4D89CD32D9 (optionnel_id), PRIMARY KEY(vehicule_id, optionnel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicule_optionnel ADD CONSTRAINT FK_8CF3FE4D4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicule_optionnel ADD CONSTRAINT FK_8CF3FE4D89CD32D9 FOREIGN KEY (optionnel_id) REFERENCES `optionnel` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule_optionnel DROP FOREIGN KEY FK_8CF3FE4D4A4A3511');
        $this->addSql('ALTER TABLE vehicule_optionnel DROP FOREIGN KEY FK_8CF3FE4D89CD32D9');
        $this->addSql('DROP TABLE vehicule_optionnel');
    }
}
