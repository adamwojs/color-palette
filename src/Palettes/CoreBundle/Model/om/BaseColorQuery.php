<?php

namespace Palettes\CoreBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Palettes\CoreBundle\Model\Color;
use Palettes\CoreBundle\Model\ColorPeer;
use Palettes\CoreBundle\Model\ColorQuery;
use Palettes\CoreBundle\Model\Palette;

/**
 * @method ColorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ColorQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ColorQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method ColorQuery orderByPaletteId($order = Criteria::ASC) Order by the palette_id column
 *
 * @method ColorQuery groupById() Group by the id column
 * @method ColorQuery groupByName() Group by the name column
 * @method ColorQuery groupByValue() Group by the value column
 * @method ColorQuery groupByPaletteId() Group by the palette_id column
 *
 * @method ColorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ColorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ColorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ColorQuery leftJoinPalette($relationAlias = null) Adds a LEFT JOIN clause to the query using the Palette relation
 * @method ColorQuery rightJoinPalette($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Palette relation
 * @method ColorQuery innerJoinPalette($relationAlias = null) Adds a INNER JOIN clause to the query using the Palette relation
 *
 * @method Color findOne(PropelPDO $con = null) Return the first Color matching the query
 * @method Color findOneOrCreate(PropelPDO $con = null) Return the first Color matching the query, or a new Color object populated from the query conditions when no match is found
 *
 * @method Color findOneByName(string $name) Return the first Color filtered by the name column
 * @method Color findOneByValue(string $value) Return the first Color filtered by the value column
 * @method Color findOneByPaletteId(int $palette_id) Return the first Color filtered by the palette_id column
 *
 * @method array findById(int $id) Return Color objects filtered by the id column
 * @method array findByName(string $name) Return Color objects filtered by the name column
 * @method array findByValue(string $value) Return Color objects filtered by the value column
 * @method array findByPaletteId(int $palette_id) Return Color objects filtered by the palette_id column
 */
abstract class BaseColorQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseColorQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Palettes\\CoreBundle\\Model\\Color';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ColorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ColorQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ColorQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ColorQuery) {
            return $criteria;
        }
        $query = new ColorQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Color|Color[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ColorPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ColorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Color A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Color A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `value`, `palette_id` FROM `color` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Color();
            $obj->hydrate($row);
            ColorPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Color|Color[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Color[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ColorPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ColorPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ColorPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ColorPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ColorPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ColorPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue('fooValue');   // WHERE value = 'fooValue'
     * $query->filterByValue('%fooValue%'); // WHERE value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $value The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($value)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $value)) {
                $value = str_replace('*', '%', $value);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ColorPeer::VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the palette_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPaletteId(1234); // WHERE palette_id = 1234
     * $query->filterByPaletteId(array(12, 34)); // WHERE palette_id IN (12, 34)
     * $query->filterByPaletteId(array('min' => 12)); // WHERE palette_id >= 12
     * $query->filterByPaletteId(array('max' => 12)); // WHERE palette_id <= 12
     * </code>
     *
     * @see       filterByPalette()
     *
     * @param     mixed $paletteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function filterByPaletteId($paletteId = null, $comparison = null)
    {
        if (is_array($paletteId)) {
            $useMinMax = false;
            if (isset($paletteId['min'])) {
                $this->addUsingAlias(ColorPeer::PALETTE_ID, $paletteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paletteId['max'])) {
                $this->addUsingAlias(ColorPeer::PALETTE_ID, $paletteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ColorPeer::PALETTE_ID, $paletteId, $comparison);
    }

    /**
     * Filter the query by a related Palette object
     *
     * @param   Palette|PropelObjectCollection $palette The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ColorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPalette($palette, $comparison = null)
    {
        if ($palette instanceof Palette) {
            return $this
                ->addUsingAlias(ColorPeer::PALETTE_ID, $palette->getId(), $comparison);
        } elseif ($palette instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ColorPeer::PALETTE_ID, $palette->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPalette() only accepts arguments of type Palette or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Palette relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function joinPalette($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Palette');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Palette');
        }

        return $this;
    }

    /**
     * Use the Palette relation Palette object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Palettes\CoreBundle\Model\PaletteQuery A secondary query class using the current class as primary query
     */
    public function usePaletteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPalette($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Palette', '\Palettes\CoreBundle\Model\PaletteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Color $color Object to remove from the list of results
     *
     * @return ColorQuery The current query, for fluid interface
     */
    public function prune($color = null)
    {
        if ($color) {
            $this->addUsingAlias(ColorPeer::ID, $color->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
