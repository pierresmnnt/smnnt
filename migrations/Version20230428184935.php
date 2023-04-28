<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428184935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adslot_advert (adslot_id INT NOT NULL, advert_id INT NOT NULL, INDEX IDX_C030DFCB3D39E1EA (adslot_id), INDEX IDX_C030DFCBD07ECCB6 (advert_id), PRIMARY KEY(adslot_id, advert_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adslot_advert ADD CONSTRAINT FK_C030DFCB3D39E1EA FOREIGN KEY (adslot_id) REFERENCES adslot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adslot_advert ADD CONSTRAINT FK_C030DFCBD07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adslot DROP FOREIGN KEY FK_939253AAD07ECCB6');
        $this->addSql('DROP INDEX IDX_939253AAD07ECCB6 ON adslot');
        $this->addSql('ALTER TABLE adslot DROP advert_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adslot_advert DROP FOREIGN KEY FK_C030DFCB3D39E1EA');
        $this->addSql('ALTER TABLE adslot_advert DROP FOREIGN KEY FK_C030DFCBD07ECCB6');
        $this->addSql('DROP TABLE adslot_advert');
        $this->addSql('ALTER TABLE adslot ADD advert_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adslot ADD CONSTRAINT FK_939253AAD07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id)');
        $this->addSql('CREATE INDEX IDX_939253AAD07ECCB6 ON adslot (advert_id)');
    }
}
