<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170703193539 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    public function up(Schema $schema)
    {
        $schema->getTable('filter')->addColumn('token', 'string');
    }

    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    public function down(Schema $schema)
    {
        $schema->getTable('filter')->dropColumn('token');
    }
}
