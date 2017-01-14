<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170114144051 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->getTable('filter')->addColumn('created_at', 'datetime');
        $schema->getTable('filter')->addColumn('active', 'boolean')->setDefault('1');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->getTable('filter')->dropColumn('created_at');
        $schema->getTable('filter')->dropColumn('active');

    }
}
