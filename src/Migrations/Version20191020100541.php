<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020100541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, target LONGTEXT NOT NULL, visit_count INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_36AC99F1D4E6F81 (address), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, link_id INT NOT NULL, total INT NOT NULL, referrers JSON NOT NULL COMMENT \'(DC2Type:json_array)\', countries JSON NOT NULL COMMENT \'(DC2Type:json_array)\', br_chrome INT NOT NULL, br_edge INT NOT NULL, br_firefox INT NOT NULL, br_ie INT NOT NULL, br_opera INT NOT NULL, br_other INT NOT NULL, br_safari INT NOT NULL, os_android INT NOT NULL, os_ios INT NOT NULL, os_linux INT NOT NULL, os_macos INT NOT NULL, os_other INT NOT NULL, os_windows INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_437EE939ADA40271 (link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939ADA40271 FOREIGN KEY (link_id) REFERENCES link (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE939ADA40271');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP TABLE visit');
    }
}
