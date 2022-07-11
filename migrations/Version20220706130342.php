<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706130342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_apocalypse ADD family_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card_apocalypse ADD CONSTRAINT FK_168DC39BC35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('CREATE INDEX IDX_168DC39BC35E566A ON card_apocalypse (family_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_apocalypse DROP FOREIGN KEY FK_168DC39BC35E566A');
        $this->addSql('DROP INDEX IDX_168DC39BC35E566A ON card_apocalypse');
        $this->addSql('ALTER TABLE card_apocalypse DROP family_id');
    }
}
