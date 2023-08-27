<?php

declare(strict_types=1);

namespace Oander\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230826102356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create database schema';
    }

    public function up(Schema $schema): void
    {
        $entitiesTable = $schema->createTable('monitors');
        $entitiesTable->addColumn('id', 'integer', array('autoincrement' => true));
        $entitiesTable->addColumn('name','text');
        $entitiesTable->setPrimaryKey(array('id'));
        
        $attributesTable = $schema->createTable('attributes');
        $attributesTable->addColumn('id', 'integer', array('autoincrement' => true));
        $attributesTable->addColumn('name','text');
        $attributesTable->addColumn('code','text');
        $attributesTable->setPrimaryKey(array('id'));
        
        $attributeValuesTable = $schema->createTable('attribute_values');
        $attributeValuesTable->addColumn('id', 'integer', array('autoincrement' => true));
        $attributeValuesTable->addColumn('attribute_id','integer');
        $attributeValuesTable->addColumn('entity_id','integer');
        $attributeValuesTable->addColumn('value','text');
        $attributeValuesTable->setPrimaryKey(array('id'));
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('entities');
        $schema->dropTable('attributes');
        $schema->dropTable('attribute_values');
    }
}
