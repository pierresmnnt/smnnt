<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220710133705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD gear_camera_id INT DEFAULT NULL, ADD gear_lens_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F24AEB290 FOREIGN KEY (gear_camera_id) REFERENCES gear (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB84D7EE1 FOREIGN KEY (gear_lens_id) REFERENCES gear (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F24AEB290 ON image (gear_camera_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FB84D7EE1 ON image (gear_lens_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F24AEB290');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB84D7EE1');
        $this->addSql('DROP INDEX IDX_C53D045F24AEB290 ON image');
        $this->addSql('DROP INDEX IDX_C53D045FB84D7EE1 ON image');
        $this->addSql('ALTER TABLE image DROP gear_camera_id, DROP gear_lens_id');
    }
}
