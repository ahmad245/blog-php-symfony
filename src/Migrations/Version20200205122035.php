<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200205122035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE like_user DROP FOREIGN KEY FK_54E60A374B89032C');
        $this->addSql('ALTER TABLE like_user DROP FOREIGN KEY FK_54E60A37F675F31B');
        $this->addSql('ALTER TABLE like_user DROP id, CHANGE post_id post_id INT NOT NULL, CHANGE author_id author_id INT NOT NULL, ADD PRIMARY KEY (post_id, author_id)');
        $this->addSql('ALTER TABLE like_user ADD CONSTRAINT FK_54E60A374B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE like_user ADD CONSTRAINT FK_54E60A37F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE like_user DROP FOREIGN KEY FK_54E60A374B89032C');
        $this->addSql('ALTER TABLE like_user DROP FOREIGN KEY FK_54E60A37F675F31B');
        $this->addSql('ALTER TABLE like_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE like_user ADD id INT NOT NULL, CHANGE post_id post_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE like_user ADD CONSTRAINT FK_54E60A374B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE like_user ADD CONSTRAINT FK_54E60A37F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE SET NULL');
    }
}
