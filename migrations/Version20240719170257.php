<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719170257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planner_recipe (id INT AUTO_INCREMENT NOT NULL, planner_id INT DEFAULT NULL, recipe_id INT DEFAULT NULL, day_id INT DEFAULT NULL, time_id INT DEFAULT NULL, INDEX IDX_97B0DB715346EAE1 (planner_id), INDEX IDX_97B0DB7159D8A214 (recipe_id), INDEX IDX_97B0DB719C24126 (day_id), INDEX IDX_97B0DB715EEADD3B (time_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planner_recipe ADD CONSTRAINT FK_97B0DB715346EAE1 FOREIGN KEY (planner_id) REFERENCES planner (id)');
        $this->addSql('ALTER TABLE planner_recipe ADD CONSTRAINT FK_97B0DB7159D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE planner_recipe ADD CONSTRAINT FK_97B0DB719C24126 FOREIGN KEY (day_id) REFERENCES week (id)');
        $this->addSql('ALTER TABLE planner_recipe ADD CONSTRAINT FK_97B0DB715EEADD3B FOREIGN KEY (time_id) REFERENCES time (id)');
        $this->addSql('ALTER TABLE planner_week DROP FOREIGN KEY FK_358DE5DB5346EAE1');
        $this->addSql('ALTER TABLE planner_week DROP FOREIGN KEY FK_358DE5DBC86F3B2F');
        $this->addSql('ALTER TABLE planner_time DROP FOREIGN KEY FK_143145E5346EAE1');
        $this->addSql('ALTER TABLE planner_time DROP FOREIGN KEY FK_143145E5EEADD3B');
        $this->addSql('DROP TABLE planner_week');
        $this->addSql('DROP TABLE planner_time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planner_week (planner_id INT NOT NULL, week_id INT NOT NULL, INDEX IDX_358DE5DBC86F3B2F (week_id), INDEX IDX_358DE5DB5346EAE1 (planner_id), PRIMARY KEY(planner_id, week_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE planner_time (planner_id INT NOT NULL, time_id INT NOT NULL, INDEX IDX_143145E5EEADD3B (time_id), INDEX IDX_143145E5346EAE1 (planner_id), PRIMARY KEY(planner_id, time_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE planner_week ADD CONSTRAINT FK_358DE5DB5346EAE1 FOREIGN KEY (planner_id) REFERENCES planner (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planner_week ADD CONSTRAINT FK_358DE5DBC86F3B2F FOREIGN KEY (week_id) REFERENCES week (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planner_time ADD CONSTRAINT FK_143145E5346EAE1 FOREIGN KEY (planner_id) REFERENCES planner (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planner_time ADD CONSTRAINT FK_143145E5EEADD3B FOREIGN KEY (time_id) REFERENCES time (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planner_recipe DROP FOREIGN KEY FK_97B0DB715346EAE1');
        $this->addSql('ALTER TABLE planner_recipe DROP FOREIGN KEY FK_97B0DB7159D8A214');
        $this->addSql('ALTER TABLE planner_recipe DROP FOREIGN KEY FK_97B0DB719C24126');
        $this->addSql('ALTER TABLE planner_recipe DROP FOREIGN KEY FK_97B0DB715EEADD3B');
        $this->addSql('DROP TABLE planner_recipe');
    }
}
