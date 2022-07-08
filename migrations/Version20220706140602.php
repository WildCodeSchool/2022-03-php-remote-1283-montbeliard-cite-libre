<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706140602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_won DROP FOREIGN KEY FK_B6601831E4D9E927');
        $this->addSql('DROP INDEX IDX_B6601831E4D9E927 ON card_won');
        $this->addSql('ALTER TABLE card_won DROP card_apocalypse_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_won ADD card_apocalypse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card_won ADD CONSTRAINT FK_B6601831E4D9E927 FOREIGN KEY (card_apocalypse_id) REFERENCES card_apocalypse (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6601831E4D9E927 ON card_won (card_apocalypse_id)');
    }
}
