<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727080458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_asked ADD answer_qcm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question_asked ADD CONSTRAINT FK_3DC6A9F4D9B7B01 FOREIGN KEY (answer_qcm_id) REFERENCES answer (id)');
        $this->addSql('CREATE INDEX IDX_3DC6A9F4D9B7B01 ON question_asked (answer_qcm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_asked DROP FOREIGN KEY FK_3DC6A9F4D9B7B01');
        $this->addSql('DROP INDEX IDX_3DC6A9F4D9B7B01 ON question_asked');
        $this->addSql('ALTER TABLE question_asked DROP answer_qcm_id');
    }
}
