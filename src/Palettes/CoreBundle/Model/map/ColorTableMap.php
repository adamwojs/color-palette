<?php

namespace Palettes\CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'color' table.
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
class ColorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Palettes.CoreBundle.Model.map.ColorTableMap';

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
        $this->setName('color');
        $this->setPhpName('Color');
        $this->setClassname('Palettes\\CoreBundle\\Model\\Color');
        $this->setPackage('src.Palettes.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 128, null);
        $this->addColumn('value', 'Value', 'VARCHAR', false, 6, null);
        $this->addForeignKey('palette_id', 'PaletteId', 'INTEGER', 'palette', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Palette', 'Palettes\\CoreBundle\\Model\\Palette', RelationMap::MANY_TO_ONE, array('palette_id' => 'id', ), null, null);
    } // buildRelations()

} // ColorTableMap
