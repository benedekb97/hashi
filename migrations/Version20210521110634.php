<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521110634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE connection (id INT AUTO_INCREMENT NOT NULL, first_island_id INT DEFAULT NULL, second_island_id INT DEFAULT NULL, count INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, archived_at DATETIME DEFAULT NULL, INDEX IDX_29F773664CE36A58 (first_island_id), INDEX IDX_29F773668D057B9E (second_island_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, archived_at DATETIME DEFAULT NULL, INDEX IDX_232B318CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE island (id INT AUTO_INCREMENT NOT NULL, point_id INT DEFAULT NULL, game_id INT DEFAULT NULL, target_bridge_count INT NOT NULL, bridge_count INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, archived_at DATETIME DEFAULT NULL, INDEX IDX_A90F6FEAC028CEA2 (point_id), INDEX IDX_A90F6FEAE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point (id INT AUTO_INCREMENT NOT NULL, vertical_position INT DEFAULT NULL, horizontal_position INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX point_unique_position (vertical_position, horizontal_position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F773664CE36A58 FOREIGN KEY (first_island_id) REFERENCES island (id)');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F773668D057B9E FOREIGN KEY (second_island_id) REFERENCES island (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE island ADD CONSTRAINT FK_A90F6FEAC028CEA2 FOREIGN KEY (point_id) REFERENCES point (id)');
        $this->addSql('ALTER TABLE island ADD CONSTRAINT FK_A90F6FEAE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE island DROP FOREIGN KEY FK_A90F6FEAE48FD905');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F773664CE36A58');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F773668D057B9E');
        $this->addSql('ALTER TABLE island DROP FOREIGN KEY FK_A90F6FEAC028CEA2');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CA76ED395');
        $this->addSql('DROP TABLE connection');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE island');
        $this->addSql('DROP TABLE point');
        $this->addSql('DROP TABLE user');
    }
}
