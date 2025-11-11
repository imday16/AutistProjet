<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251111202741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_topic_status (id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, moderated_by_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, reason LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A146F4291F55203D (topic_id), INDEX IDX_A146F4298EDA19B0 (moderated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_status (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, moderated_by_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, reason LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B1133D0EF8697D13 (comment_id), INDEX IDX_B1133D0E8EDA19B0 (moderated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_topic_status ADD CONSTRAINT FK_A146F4291F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE admin_topic_status ADD CONSTRAINT FK_A146F4298EDA19B0 FOREIGN KEY (moderated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_status ADD CONSTRAINT FK_B1133D0EF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment_status ADD CONSTRAINT FK_B1133D0E8EDA19B0 FOREIGN KEY (moderated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_topic_status DROP FOREIGN KEY FK_A146F4291F55203D');
        $this->addSql('ALTER TABLE admin_topic_status DROP FOREIGN KEY FK_A146F4298EDA19B0');
        $this->addSql('ALTER TABLE comment_status DROP FOREIGN KEY FK_B1133D0EF8697D13');
        $this->addSql('ALTER TABLE comment_status DROP FOREIGN KEY FK_B1133D0E8EDA19B0');
        $this->addSql('DROP TABLE admin_topic_status');
        $this->addSql('DROP TABLE comment_status');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
    }
}
