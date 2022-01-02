<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102213511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD description VARCHAR(255) DEFAULT NULL, ADD camera VARCHAR(255) DEFAULT NULL, ADD lens VARCHAR(255) DEFAULT NULL, ADD exposure VARCHAR(255) DEFAULT NULL, ADD aperture VARCHAR(255) DEFAULT NULL, ADD iso INT DEFAULT NULL, ADD focal INT DEFAULT NULL, ADD date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP description, DROP camera, DROP lens, DROP exposure, DROP aperture, DROP iso, DROP focal, DROP date');
    }
}
