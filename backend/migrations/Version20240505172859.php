<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505172859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id UUID NOT NULL, path VARCHAR(255) NOT NULL, content TEXT NOT NULL, executable BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN file.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE recipe (id UUID NOT NULL, vendor VARCHAR(255) NOT NULL, package_name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, ref VARCHAR(32) NOT NULL, manifest_bundle VARCHAR(255) NOT NULL, manifest_bundle_evn VARCHAR(255) NOT NULL, manifest_copy_from_recipe JSON NOT NULL, manifest_copy_from_package JSON NOT NULL, manifest_env JSON NOT NULL, manifest_gitignore JSON NOT NULL, manifest_composer_scripts JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA88B137F52233F6E56E1BCEBF1CD3C3 ON recipe (vendor, package_name, version)');
        $this->addSql('COMMENT ON COLUMN recipe.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE recipe_file (recipe_id UUID NOT NULL, file_id UUID NOT NULL, PRIMARY KEY(recipe_id, file_id))');
        $this->addSql('CREATE INDEX IDX_F3846CCF59D8A214 ON recipe_file (recipe_id)');
        $this->addSql('CREATE INDEX IDX_F3846CCF93CB796C ON recipe_file (file_id)');
        $this->addSql('COMMENT ON COLUMN recipe_file.recipe_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN recipe_file.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE recipe_file ADD CONSTRAINT FK_F3846CCF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe_file ADD CONSTRAINT FK_F3846CCF93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_file DROP CONSTRAINT FK_F3846CCF59D8A214');
        $this->addSql('ALTER TABLE recipe_file DROP CONSTRAINT FK_F3846CCF93CB796C');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_file');
    }
}
