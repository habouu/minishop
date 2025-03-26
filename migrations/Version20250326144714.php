<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326144714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detail (id INT AUTO_INCREMENT NOT NULL, purchase_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_image VARCHAR(255) NOT NULL, product_qty INT NOT NULL, product_price DOUBLE PRECISION NOT NULL, product_tva DOUBLE PRECISION NOT NULL, INDEX IDX_2E067F93558FBEB9 (purchase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F93558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE purchase CHANGE carriername carrier_name VARCHAR(255) NOT NULL, CHANGE carrierprice carrier_price DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail DROP FOREIGN KEY FK_2E067F93558FBEB9');
        $this->addSql('DROP TABLE detail');
        $this->addSql('ALTER TABLE purchase CHANGE carrier_name carriername VARCHAR(255) NOT NULL, CHANGE carrier_price carrierprice DOUBLE PRECISION NOT NULL');
    }
}
