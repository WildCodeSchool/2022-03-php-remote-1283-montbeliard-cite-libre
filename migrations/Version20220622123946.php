<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220622123946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', duration TIME DEFAULT NULL, score INT DEFAULT NULL, type VARCHAR(45) DEFAULT NULL, INDEX IDX_232B318CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_asked (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, game_id INT DEFAULT NULL, INDEX IDX_3DC6A9F41E27F6BF (question_id), INDEX IDX_3DC6A9F4E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question_asked ADD CONSTRAINT FK_3DC6A9F41E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_asked ADD CONSTRAINT FK_3DC6A9F4E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE answer CHANGE answer content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE question content VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_asked DROP FOREIGN KEY FK_3DC6A9F4E48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE question_asked');
        $this->addSql('ALTER TABLE answer CHANGE content answer LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE content question VARCHAR(255) NOT NULL');
    }
}
