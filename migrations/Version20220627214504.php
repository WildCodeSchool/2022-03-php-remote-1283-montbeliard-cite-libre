<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627214504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card_apocalypse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, rule JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_won (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, card_apocalypse_id INT DEFAULT NULL, INDEX IDX_B6601831E48FD905 (game_id), INDEX IDX_B6601831E4D9E927 (card_apocalypse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_won ADD CONSTRAINT FK_B6601831E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE card_won ADD CONSTRAINT FK_B6601831E4D9E927 FOREIGN KEY (card_apocalypse_id) REFERENCES card_apocalypse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_won DROP FOREIGN KEY FK_B6601831E4D9E927');
        $this->addSql('DROP TABLE card_apocalypse');
        $this->addSql('DROP TABLE card_won');
    }
}
