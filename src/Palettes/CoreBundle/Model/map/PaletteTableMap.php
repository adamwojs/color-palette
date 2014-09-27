<?php

namespace Palettes\CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'palette' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Palettes.CoreBundle.Model.map
 */
class PaletteTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Palettes.CoreBundle.Model.map.PaletteTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('palette');
        $this->setPhpName('Palette');
        $this->setClassname('Palettes\\CoreBundle\\Model\\Palette');
        $this->setPackage('src.Palettes.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 256, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 1024, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Color', 'Palettes\\CoreBundle\\Model\\Color', RelationMap::ONE_TO_MANY, array('id' => 'palette_id', ), null, null, 'Colors');
    } // buildRelations()

} // PaletteTableMap
