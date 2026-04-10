<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260410225741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD city VARCHAR(255) DEFAULT NULL, ADD zip_code VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, DROP balance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD balance DOUBLE PRECISION NOT NULL, DROP city, DROP zip_code, DROP address');
    }
}
