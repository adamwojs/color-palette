<?php

namespace Palettes\CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'palette_tag' table.
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
class PaletteTagTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Palettes.CoreBundle.Model.map.PaletteTagTableMap';

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
        $this->setName('palette_tag');
        $this->setPhpName('PaletteTag');
        $this->setClassname('Palettes\\CoreBundle\\Model\\PaletteTag');
        $this->setPackage('src.Palettes.CoreBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('palette_id', 'PaletteId', 'INTEGER' , 'palette', 'id', true, null, null);
        $this->addForeignPrimaryKey('tag_id', 'TagId', 'INTEGER' , 'tag', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Palette', 'Palettes\\CoreBundle\\Model\\Palette', RelationMap::MANY_TO_ONE, array('palette_id' => 'id', ), null, null);
        $this->addRelation('Tag', 'Palettes\\CoreBundle\\Model\\Tag', RelationMap::MANY_TO_ONE, array('tag_id' => 'id', ), null, null);
    } // buildRelations()

} // PaletteTagTableMap
