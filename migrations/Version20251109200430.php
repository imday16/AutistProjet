<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251109200430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topic_vote (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, user_id INT DEFAULT NULL, vote_type VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_62FBE4D11F55203D (topic_id), INDEX IDX_62FBE4D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE topic_vote ADD CONSTRAINT FK_62FBE4D11F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE topic_vote ADD CONSTRAINT FK_62FBE4D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic_vote DROP FOREIGN KEY FK_62FBE4D11F55203D');
        $this->addSql('ALTER TABLE topic_vote DROP FOREIGN KEY FK_62FBE4D1A76ED395');
        $this->addSql('DROP TABLE topic_vote');
    }
}
