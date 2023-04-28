<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428114809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adslot ADD advert_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adslot ADD CONSTRAINT FK_939253AAD07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id)');
        $this->addSql('CREATE INDEX IDX_939253AAD07ECCB6 ON adslot (advert_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adslot DROP FOREIGN KEY FK_939253AAD07ECCB6');
        $this->addSql('DROP INDEX IDX_939253AAD07ECCB6 ON adslot');
        $this->addSql('ALTER TABLE adslot DROP advert_id');
    }
}
