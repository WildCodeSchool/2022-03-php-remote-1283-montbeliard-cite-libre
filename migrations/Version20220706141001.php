<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706141001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_won ADD card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card_won ADD CONSTRAINT FK_B66018314ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('CREATE INDEX IDX_B66018314ACC9A20 ON card_won (card_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_won DROP FOREIGN KEY FK_B66018314ACC9A20');
        $this->addSql('DROP INDEX IDX_B66018314ACC9A20 ON card_won');
        $this->addSql('ALTER TABLE card_won DROP card_id');
    }
}
