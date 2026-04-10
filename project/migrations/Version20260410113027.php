<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260410113027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent ADD agency_id INT NOT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DCDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9DCDEADB2A ON agent (agency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DCDEADB2A');
        $this->addSql('DROP INDEX IDX_268B9C9DCDEADB2A ON agent');
        $this->addSql('ALTER TABLE agent DROP agency_id');
    }
}
