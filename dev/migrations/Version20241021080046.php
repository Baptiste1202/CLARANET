<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021080046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `optionnel` (id INT AUTO_INCREMENT NOT NULL, label_option VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule_optionnel (vehicule_id INT NOT NULL, optionnel_id INT NOT NULL, INDEX IDX_8CF3FE4D4A4A3511 (vehicule_id), INDEX IDX_8CF3FE4D89CD32D9 (optionnel_id), PRIMARY KEY(vehicule_id, optionnel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicule_optionnel ADD CONSTRAINT FK_8CF3FE4D4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicule_optionnel ADD CONSTRAINT FK_8CF3FE4D89CD32D9 FOREIGN KEY (optionnel_id) REFERENCES `optionnel` (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('ALTER TABLE brand CHANGE brand label_brand VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE vehicules CHANGE wheel_id wheel_id INT NOT NULL');
        $this->addSql('ALTER TABLE vehicules ADD CONSTRAINT FK_78218C2D9AF5772F FOREIGN KEY (wheel_id) REFERENCES wheel (id)');
        $this->addSql('CREATE INDEX IDX_78218C2D9AF5772F ON vehicules (wheel_id)');
        $this->addSql('ALTER TABLE wheel CHANGE brand_id brand_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, label_option VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vehicule_optionnel DROP FOREIGN KEY FK_8CF3FE4D4A4A3511');
        $this->addSql('ALTER TABLE vehicule_optionnel DROP FOREIGN KEY FK_8CF3FE4D89CD32D9');
        $this->addSql('DROP TABLE `optionnel`');
        $this->addSql('DROP TABLE vehicule_optionnel');
        $this->addSql('ALTER TABLE brand CHANGE label_brand brand VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE wheel CHANGE brand_id brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicules DROP FOREIGN KEY FK_78218C2D9AF5772F');
        $this->addSql('DROP INDEX IDX_78218C2D9AF5772F ON vehicules');
        $this->addSql('ALTER TABLE vehicules CHANGE wheel_id wheel_id VARCHAR(255) DEFAULT NULL');
    }
}
