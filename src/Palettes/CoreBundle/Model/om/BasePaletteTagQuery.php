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
use Palettes\CoreBundle\Model\Palette;
use Palettes\CoreBundle\Model\PaletteTag;
use Palettes\CoreBundle\Model\PaletteTagPeer;
use Palettes\CoreBundle\Model\PaletteTagQuery;
use Palettes\CoreBundle\Model\Tag;

/**
 * @method PaletteTagQuery orderByPaletteId($order = Criteria::ASC) Order by the palette_id column
 * @method PaletteTagQuery orderByTagId($order = Criteria::ASC) Order by the tag_id column
 *
 * @method PaletteTagQuery groupByPaletteId() Group by the palette_id column
 * @method PaletteTagQuery groupByTagId() Group by the tag_id column
 *
 * @method PaletteTagQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PaletteTagQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PaletteTagQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PaletteTagQuery leftJoinPalette($relationAlias = null) Adds a LEFT JOIN clause to the query using the Palette relation
 * @method PaletteTagQuery rightJoinPalette($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Palette relation
 * @method PaletteTagQuery innerJoinPalette($relationAlias = null) Adds a INNER JOIN clause to the query using the Palette relation
 *
 * @method PaletteTagQuery leftJoinTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the Tag relation
 * @method PaletteTagQuery rightJoinTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Tag relation
 * @method PaletteTagQuery innerJoinTag($relationAlias = null) Adds a INNER JOIN clause to the query using the Tag relation
 *
 * @method PaletteTag findOne(PropelPDO $con = null) Return the first PaletteTag matching the query
 * @method PaletteTag findOneOrCreate(PropelPDO $con = null) Return the first PaletteTag matching the query, or a new PaletteTag object populated from the query conditions when no match is found
 *
 * @method PaletteTag findOneByPaletteId(int $palette_id) Return the first PaletteTag filtered by the palette_id column
 * @method PaletteTag findOneByTagId(int $tag_id) Return the first PaletteTag filtered by the tag_id column
 *
 * @method array findByPaletteId(int $palette_id) Return PaletteTag objects filtered by the palette_id column
 * @method array findByTagId(int $tag_id) Return PaletteTag objects filtered by the tag_id column
 */
abstract class BasePaletteTagQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePaletteTagQuery object.
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
            $modelName = 'Palettes\\CoreBundle\\Model\\PaletteTag';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PaletteTagQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PaletteTagQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PaletteTagQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PaletteTagQuery) {
            return $criteria;
        }
        $query = new PaletteTagQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$palette_id, $tag_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PaletteTag|PaletteTag[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PaletteTagPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PaletteTagPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PaletteTag A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `palette_id`, `tag_id` FROM `palette_tag` WHERE `palette_id` = :p0 AND `tag_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new PaletteTag();
            $obj->hydrate($row);
            PaletteTagPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return PaletteTag|PaletteTag[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|PaletteTag[]|mixed the list of results, formatted by the current formatter
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
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PaletteTagPeer::PALETTE_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PaletteTagPeer::TAG_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PaletteTagPeer::PALETTE_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PaletteTagPeer::TAG_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function filterByPaletteId($paletteId = null, $comparison = null)
    {
        if (is_array($paletteId)) {
            $useMinMax = false;
            if (isset($paletteId['min'])) {
                $this->addUsingAlias(PaletteTagPeer::PALETTE_ID, $paletteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paletteId['max'])) {
                $this->addUsingAlias(PaletteTagPeer::PALETTE_ID, $paletteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaletteTagPeer::PALETTE_ID, $paletteId, $comparison);
    }

    /**
     * Filter the query on the tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTagId(1234); // WHERE tag_id = 1234
     * $query->filterByTagId(array(12, 34)); // WHERE tag_id IN (12, 34)
     * $query->filterByTagId(array('min' => 12)); // WHERE tag_id >= 12
     * $query->filterByTagId(array('max' => 12)); // WHERE tag_id <= 12
     * </code>
     *
     * @see       filterByTag()
     *
     * @param     mixed $tagId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function filterByTagId($tagId = null, $comparison = null)
    {
        if (is_array($tagId)) {
            $useMinMax = false;
            if (isset($tagId['min'])) {
                $this->addUsingAlias(PaletteTagPeer::TAG_ID, $tagId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tagId['max'])) {
                $this->addUsingAlias(PaletteTagPeer::TAG_ID, $tagId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaletteTagPeer::TAG_ID, $tagId, $comparison);
    }

    /**
     * Filter the query by a related Palette object
     *
     * @param   Palette|PropelObjectCollection $palette The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PaletteTagQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPalette($palette, $comparison = null)
    {
        if ($palette instanceof Palette) {
            return $this
                ->addUsingAlias(PaletteTagPeer::PALETTE_ID, $palette->getId(), $comparison);
        } elseif ($palette instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaletteTagPeer::PALETTE_ID, $palette->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function joinPalette($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePaletteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPalette($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Palette', '\Palettes\CoreBundle\Model\PaletteQuery');
    }

    /**
     * Filter the query by a related Tag object
     *
     * @param   Tag|PropelObjectCollection $tag The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PaletteTagQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTag($tag, $comparison = null)
    {
        if ($tag instanceof Tag) {
            return $this
                ->addUsingAlias(PaletteTagPeer::TAG_ID, $tag->getId(), $comparison);
        } elseif ($tag instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaletteTagPeer::TAG_ID, $tag->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTag() only accepts arguments of type Tag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Tag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function joinTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Tag');

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
            $this->addJoinObject($join, 'Tag');
        }

        return $this;
    }

    /**
     * Use the Tag relation Tag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Palettes\CoreBundle\Model\TagQuery A secondary query class using the current class as primary query
     */
    public function useTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Tag', '\Palettes\CoreBundle\Model\TagQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PaletteTag $paletteTag Object to remove from the list of results
     *
     * @return PaletteTagQuery The current query, for fluid interface
     */
    public function prune($paletteTag = null)
    {
        if ($paletteTag) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PaletteTagPeer::PALETTE_ID), $paletteTag->getPaletteId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PaletteTagPeer::TAG_ID), $paletteTag->getTagId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
