<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181230112601 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE members (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, basket_type VARCHAR(255) DEFAULT NULL, number_basket_collected INT NULL, number_basket_compouned INT NULL, town VARCHAR(255) NOT NULL, day_of_week VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prod_bask_comp (id INT AUTO_INCREMENT NOT NULL, members_id INT NOT NULL, name_prod VARCHAR(255) NOT NULL, kg_or_unity VARCHAR(255) NOT NULL, quantity_prod INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_92C21C5FBD01F5ED (members_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prod_bask_comp ADD CONSTRAINT FK_92C21C5FBD01F5ED FOREIGN KEY (members_id) REFERENCES members (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prod_bask_comp DROP FOREIGN KEY FK_92C21C5FBD01F5ED');
        $this->addSql('DROP TABLE members');
        $this->addSql('DROP TABLE prod_bask_comp');
    }
}
