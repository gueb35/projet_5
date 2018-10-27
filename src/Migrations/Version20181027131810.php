<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181027131810 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE basket_comp (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, name_prod_1 VARCHAR(255) DEFAULT NULL, unity_prod_1 INT DEFAULT NULL, name_prod_2 VARCHAR(255) DEFAULT NULL, unity_prod_2 INT DEFAULT NULL, name_prod_3 VARCHAR(255) DEFAULT NULL, unity_prod_3 INT DEFAULT NULL, name_prod_4 VARCHAR(255) DEFAULT NULL, unity_prod_4 INT DEFAULT NULL, name_prod_5 VARCHAR(255) DEFAULT NULL, unity_prod_5 INT DEFAULT NULL, name_prod_6 VARCHAR(255) DEFAULT NULL, unity_prod_6 INT DEFAULT NULL, name_prod_7 VARCHAR(255) DEFAULT NULL, unity_prod_7 INT DEFAULT NULL, name_prod_8 VARCHAR(255) DEFAULT NULL, unity_prod_8 INT DEFAULT NULL, name_prod_9 VARCHAR(255) DEFAULT NULL, unity_prod_9 INT DEFAULT NULL, name_prod_10 VARCHAR(255) DEFAULT NULL, unity_prod_10 INT DEFAULT NULL, name_prod_11 VARCHAR(255) DEFAULT NULL, kg_prod_11 INT DEFAULT NULL, name_prod_12 VARCHAR(255) DEFAULT NULL, kg_prod_12 INT DEFAULT NULL, name_prod_13 VARCHAR(255) DEFAULT NULL, kg_prod_13 INT DEFAULT NULL, name_prod_14 VARCHAR(255) DEFAULT NULL, kg_prod_14 INT DEFAULT NULL, name_prod_15 VARCHAR(255) DEFAULT NULL, kg_prod_15 INT DEFAULT NULL, name_prod_16 VARCHAR(255) DEFAULT NULL, kg_prod_16 INT DEFAULT NULL, name_prod_17 VARCHAR(255) DEFAULT NULL, kg_prod_17 INT DEFAULT NULL, name_prod_18 VARCHAR(255) DEFAULT NULL, kg_prod_18 INT DEFAULT NULL, name_prod_19 VARCHAR(255) DEFAULT NULL, kg_prod_19 INT DEFAULT NULL, name_prod_20 VARCHAR(255) DEFAULT NULL, kg_prod_20 INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE basket_comp');
    }
}
