<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170924075153 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, available_filter_count INT NOT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, site_id INT DEFAULT NULL, url VARCHAR(1000) NOT NULL, filter_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, token VARCHAR(255) NOT NULL, deactivation_token VARCHAR(255) NOT NULL, INDEX IDX_7FC45F1DA76ED395 (user_id), INDEX IDX_7FC45F1DF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE results (id INT AUTO_INCREMENT NOT NULL, filter_id INT DEFAULT NULL, is_new TINYINT(1) DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, title LONGTEXT DEFAULT NULL, added_on DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9FA3E414F47645AE (url), INDEX IDX_9FA3E414D395B25E (filter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE websites (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, site_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filter ADD CONSTRAINT FK_7FC45F1DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE filter ADD CONSTRAINT FK_7FC45F1DF6BD1646 FOREIGN KEY (site_id) REFERENCES websites (id)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E414D395B25E FOREIGN KEY (filter_id) REFERENCES filter (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filter DROP FOREIGN KEY FK_7FC45F1DA76ED395');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E414D395B25E');
        $this->addSql('ALTER TABLE filter DROP FOREIGN KEY FK_7FC45F1DF6BD1646');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE results');
        $this->addSql('DROP TABLE websites');
    }
}
