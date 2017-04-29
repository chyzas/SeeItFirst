<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170327163243 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->getTable('results')->addColumn('item_id', 'text');
        $schema->getTable('results')->dropIndex('UNIQ_9FA3E414F47645AE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->getTable('results')->dropColumn('item_id');
        $schema->getTable('results')->addIndex(['url']);
    }
}
