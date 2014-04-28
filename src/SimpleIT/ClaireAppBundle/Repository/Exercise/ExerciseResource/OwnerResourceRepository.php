<?php

namespace SimpleIT\ExerciseBundle\Repository\ExerciseResource;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\MetadataConstraint;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectConstraints;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectId;
use SimpleIT\CommonBundle\Entity\User;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ExerciseBundle\Entity\ExerciseResource\OwnerResource;
use SimpleIT\ExerciseBundle\Exception\FilterException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * OwnerResource Repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceRepository extends BaseRepository
{
    /**
     * Find an owner resource by its id
     *
     * @param mixed $ownerResourceId
     *
     * @return OwnerResource
     * @throws NonExistingObjectException
     */
    public function find($ownerResourceId)
    {
        $ownerResource = parent::find($ownerResourceId);
        if ($ownerResource === null) {
            throw new NonExistingObjectException();
        }

        return $ownerResource;
    }

    /**
     * Find a list of resources according to a type an to metadata contained in a
     * collection information
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $owner
     * @param ExerciseResource      $resource
     *
     * @throws FilterException
     * @return array
     */
    public function findAll(
        CollectionInformation $collectionInformation = null,
        $owner = null,
        $resource = null
    )
    {
        $metadata = array();
        $keywords = array();

        $qb = $this->createQueryBuilder('owr');
        $qb->leftJoin('owr.resource', 'r');

        if (!is_null($owner)) {
            $qb->where(
                $qb->expr()->eq(
                    'owr.owner',
                    $owner->getId()
                )
            );
        }

        if (!is_null($resource)) {
            $qb->andWhere(
                $qb->expr()->eq(
                    'owr.resource',
                    $resource->getId()
                )
            );
        }

        // Collection information
        if ($collectionInformation !== null) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case ('type'):
                        if (is_array($value)) {
                            $qpType = '';
                            foreach ($value as $val) {
                                if ($qpType !== '') {
                                    $qpType = $qb->expr()->orX(
                                        $qpType,
                                        $qb->expr()->eq('r.type', "'" . $val . "'")
                                    );
                                } else {
                                    $qpType = $qb->expr()->eq('r.type', "'" . $val . "'");
                                }
                            }
                            $qb->andWhere($qpType);
                        } else {
                            $qb->andWhere($qb->expr()->eq('r.type', "'" . $value . "'"));
                        }
                        break;
                    case ('metadata'):
                        $metadata = $this->metadataToArray($value);
                        break;
                    case ('keywords'):
                        $keywords = $this->keywordsToArray($value);
                        break;
                    case ('public-except-user'):
                        // resources not covered by ownerResources
                        if (!is_numeric($value)) {
                            throw new FilterException('public-except-user filter must be numeric');
                        }
                        $qb = $this->addPublicExceptUser($qb, $value);
                        break;
                }
            }
            $sorts = $collectionInformation->getSorts();
        } else {
            $sorts = null;
        }

        // Metadata
        $i = 0;
        foreach ($metadata as $metaKey => $value) {
            $alias = 'm' . $i;
            $qb->leftJoin('owr.metadata', $alias);

            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq($alias . '.key', "'" . $metaKey . "'"),
                    $qb->expr()->eq($alias . '.value', "'" . $value . "'")
                )
            );

            $i++;
        }

        // Misc keywords
        foreach ($keywords as $keyword) {
            $alias = 'm' . $i;
            $qb->leftJoin('owr.metadata', $alias);

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq($alias . '.key', "'" . $keyword . "'"),
                    $qb->expr()->like($alias . '.value', "'%" . $keyword . "%'")
                )
            );

            $i++;
        }

        if (empty($sorts)) {
            $qb->addOrderBy('owr.id', 'ASC');
        } else {
            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'type':
                        $qb->addOrderBy('r.type', $sort->getOrder());
                        break;
                    case 'author':
                        $qb->addOrderBy('r.author', $sort->getOrder());
                        break;
                    case 'id':
                        $qb->addOrderBy('r.id', $sort->getOrder());
                        break;
                }
            }
        }
        $qb = $this->setRange($qb, $collectionInformation);

        $qb->addGroupBy('owr.id, r.id');

        return new Paginator($qb);
    }

    /**
     * Get all the exercise resources matching constraints
     *
     * @param ObjectConstraints $objectConstraints
     * @param User              $owner
     *
     * @return array An array of OwnerResource which match the constraints
     */
    public function findResourcesFromConstraintsByOwner(
        ObjectConstraints $objectConstraints,
        User $owner
    )
    {
        $qb = $this->createQueryBuilder('owr')
            ->select();
        $qb->leftJoin('owr.resource', 'r');

        $qb->where($qb->expr()->eq('owr.owner', $owner->getId()));

        // Type of the resources (if any specified)
        if ($objectConstraints->getType() != null) {
            $qb->andWhere(
                $qb->expr()->eq(
                    'r.type',
                    "'"
                    . $objectConstraints->getType()
                    . "'"
                )
            );
        }

        // Metadata
        $queryMetadataPart = '';
        $numberOfMetadataConstraints = 0;

        $metadataConstraints = $objectConstraints->getMetadataConstraints();

        foreach ($metadataConstraints as $mdc) {
            $this->addMetadataConstraintFromMdc(
                $qb,
                $queryMetadataPart,
                $mdc
            );
            $numberOfMetadataConstraints++;
        }

        // Excluded resources
        $queryExcludedPart = '';
        $excludedList = $objectConstraints->getExcluded();

        $numberOfExcluded =
            $this->addExcluded(
                $qb,
                $queryExcludedPart,
                $excludedList
            );

        // Finalization of the query
        $this->finalizeConstraintsQuery(
            $qb,
            $numberOfMetadataConstraints,
            $numberOfExcluded,
            $queryMetadataPart,
            $queryExcludedPart
        );

        return $qb->getQuery()->getResult();
    }

    /**
     * Finalize the query by adding the query parts and calculating the product
     * in the count
     *
     * @param QueryBuilder $qb
     * @param int          $numberOfMetadataConstraints
     * @param int          $numberOfExcluded
     * @param string       $queryMetadataPart
     * @param string       $queryExcludedPart
     */
    private function finalizeConstraintsQuery(
        &$qb,
        $numberOfMetadataConstraints,
        $numberOfExcluded,
        $queryMetadataPart,
        $queryExcludedPart
    )
    {
        // Add metadata part of the query if there are metadata constraints
        if ($numberOfMetadataConstraints > 0) {
            $qb->andWhere($queryMetadataPart);
        }

        // Add excluded part of the query if there are excluded
        if ($numberOfExcluded > 0) {
            $qb->andWhere($queryExcludedPart);
        }

        // If there is any part to add to the query, add the "group by" clause
        if ($queryMetadataPart != '') {
            $qb->addGroupBy('r.id, owr.id')
                ->having
                (
                    $qb->expr()->eq
                        (
                            $qb->expr()->count('r.id'),
                            $numberOfMetadataConstraints
                        )
                );
        }
    }

    /**
     * Add a metadata constraint to a query part from a MetadataConstraint
     * object
     *
     * @param QueryBuilder       $qb
     * @param string             $queryPart
     * @param MetadataConstraint $mdc
     */
    private function addMetadataConstraintFromMdc(
        &$qb,
        &$queryPart,
        MetadataConstraint $mdc
    )
    {
        $comp = $mdc->getComparator();
        $metaKey = $mdc->getKey();
        $val = $mdc->getValues();

        // in, between or exists ($val == null)
        if ($comp == 'in' || $comp == 'exists' || $comp == 'between') {
            $this->addMetadataConstraint(
                $qb,
                $queryPart,
                $metaKey,
                $comp,
                $val
            );
        } // lte, lt, gte, gt
        else {
            $this->addMetadataConstraint(
                $qb,
                $queryPart,
                $metaKey,
                $comp,
                $val[0]
            );
        }
    }

    /**
     * Completes the part of the request about metadata. Insert the LEFT JOIN in the
     * request if metadata constraints are added.
     *
     * @param QueryBuilder $qb             The queryBuilder
     * @param string       $queryPart      The query part about metadata to be completed
     * @param string       $metaKey        The metadata key
     * @param string       $comparison     Name of a comparison function of Expr from queryBuilder ('in', 'eq', 'lte', 'lt', 'gte' or 'gt')
     * @param mixed        $value          The value or array of value
     *
     * @return int The number of criteria that are added to the request part
     */
    private function addMetadataConstraint(
        &$qb,
        &$queryPart,
        $metaKey,
        $comparison,
        $value
    )
    {
        // if exists (key test only)
        if ($comparison == 'exists') {
            $qp = $qb->expr()->eq('m.key', "'" . $metaKey . "'");
        } //else (key and value test)
        else {
            // if between
            if ($comparison == 'between') {
                $exprComp = $qb->expr()->between('m.value', $value[0], $value[1]);
            } // if not between
            else {
                $exprComp = $qb->expr()->$comparison('m.value', $value);
            }

            // AND of value test and key test
            $qp = $qb->expr()->andX(
                $qb->expr()->eq('m.key', "'" . $metaKey . "'"),
                $exprComp
            );
        }

        // add to the query part
        if ($queryPart == '') {
            $queryPart = $qp;
            $qb->leftJoin(
                'owr.metadata',
                'm'
            );
        } else {
            $queryPart = $qb->expr()->orX(
                $queryPart,
                $qp
            );
        }
    }

    /**
     * Completes the part of the request about excluded. Insert the LEFT JOIN in the
     * request if exclude constraints are added.
     *
     * @param QueryBuilder $qb           The queryBuilder
     * @param string       $queryPart    The query part about excluded to be completed
     * @param array        $excludedList An array of ObjectId that are excluded
     *
     * @return int The number of criteria that are added to the request part
     */
    private function addExcluded(
        &$qb,
        &$queryPart,
        array $excludedList
    )
    {
        $numberOfExcluded = 0;

        foreach ($excludedList as $objId) {
            /** @var ObjectId $objId */
            $qp = $qb->expr()->neq('r.id', $objId->getId());

            if ($queryPart == '') {
                $queryPart = $qp;
            } else {
                $queryPart = $qb->expr()->andX($queryPart, $qp);
            }

            $numberOfExcluded++;
        }

        return $numberOfExcluded;
    }

    /**
     * Find a resource if it is owned by the user
     *
     * @param      $resourceId
     * @param User $owner
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndOwner($resourceId, User $owner)
    {
        $queryBuilder = $this->createQueryBuilder('owr');
        $queryBuilder->leftJoin(
            'owr.resource',
            'r'

        );

        $queryBuilder->where($queryBuilder->expr()->eq('owr.owner', $owner->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('r.id', $resourceId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find resource ' . $resourceId .
            ' for owner ' . $owner->getId());
        } else {
            return $result[0];
        }
    }

    /**
     * Find an owner resource by resource
     *
     * @param int              $ownerResourceId
     * @param ExerciseResource $resource
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndResource($ownerResourceId, ExerciseResource $resource)
    {
        $queryBuilder = $this->createQueryBuilder('owr');

        $queryBuilder->where($queryBuilder->expr()->eq('owr.resource', $resource->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('owr.id', $ownerResourceId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find owner resource ' .
            $ownerResourceId . ' for resource ' . $resource->getId());
        } else {
            return $result[0];
        }
    }

    /**
     * Converts the content of the metadata filter into a key => value array
     *
     * @param string|array $metadata
     *
     * @return array
     */
    private function metadataToArray($metadata)
    {
        $metadataArray = array();
        if (is_array($metadata)) {
            foreach ($metadata as $md) {
                $explode = explode(':', $md);
                $metadataArray[$explode[0]] = $explode[1];
            }
        } else {
            $explode = explode(':', $metadata);
            $metadataArray[$explode[0]] = $explode[1];
        }

        return $metadataArray;
    }

    /**
     * Convert the content of keywords filter into an array
     *
     * @param string|array $keywords
     *
     * @return array
     */
    private function keywordsToArray($keywords)
    {
        if (is_array($keywords)) {
            return $keywords;
        } else {
            return array($keywords);
        }
    }

    /**
     * Add the join and the constraints to the query builder to exclude resources already covered
     * by owner resources
     *
     * @param QueryBuilder $qb
     * @param              $userId
     *
     * @return QueryBuilder
     */
    private function addPublicExceptUser(QueryBuilder $qb, $userId)
    {
        $qb->andWhere(
            $qb->expr()->eq(
                'owr.public',
                'true'
            )
        );

        $notIn = $this->getEntityManager()->createQueryBuilder()
            ->select('re.id')
            ->from(
                'SimpleIT\ExerciseBundle\Entity\ExerciseResource\OwnerResource',
                'owRes'
            )
            ->join('owRes.resource', 're')
            ->andWhere(
                $qb->expr()->eq(
                    'owRes.owner',
                    $userId
                )
            )
            ->getQuery()
            ->getDQL();

        $qb->andWhere($qb->expr()->notIn('owr.resource', $notIn));

        return $qb;
    }
}
