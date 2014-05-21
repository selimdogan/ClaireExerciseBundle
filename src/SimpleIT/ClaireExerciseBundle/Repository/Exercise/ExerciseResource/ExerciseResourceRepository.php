<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\MetadataConstraint;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseResource repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResourceRepository extends BaseRepository
{
    /**
     * Find a resource by id
     *
     * @param mixed $resourceId
     *
     * @return ExerciseResource
     * @throws NonExistingObjectException
     */
    public function find($resourceId)
    {
        $resource = parent::find($resourceId);
        if ($resource === null) {
            throw new NonExistingObjectException();
        }

        return $resource;
    }

    /**
     * Return all the resources
     *
     * @param CollectionInformation $collectionInformation
     * @param User                  $owner
     * @param User                  $author
     * @param ExerciseResource      $parent
     * @param ExerciseResource      $forkFrom
     * @param boolean               $isRoot
     * @param boolean               $isPointer
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\FilterException
     * @return PaginatorInterface
     */
    public function findAll(
        $collectionInformation = null,
        $owner = null,
        $author = null,
        $parent = null,
        $forkFrom = null,
        $isRoot = null,
        $isPointer = null
    )
    {
        $metadata = array();
        $keywords = array();
        
        $qb = $this->createQueryBuilder('r');

        if (!is_null($owner)) {
            $qb->where(
                $qb->expr()->eq(
                    'r.owner',
                    $owner->getId()
                )
            );
        }

        if (!is_null($author)) {
            $qb->where(
                $qb->expr()->eq(
                    'r.author',
                    $author->getId()
                )
            );
        }

        if (!is_null($parent)) {
            $qb->where(
                $qb->expr()->eq(
                    'r.parent',
                    $parent->getId()
                )
            );
        }

        if (!is_null($forkFrom)) {
            $qb->where(
                $qb->expr()->eq(
                    'r.forkFrom',
                    $forkFrom->getId()
                )
            );
        }

        if ($isPointer === true) {
            $qb->where($qb->expr()->isNotNull('r.parent'));
        } else {
            $qb->where($qb->expr()->isNotNull('r.content'));
        }

        if ($isRoot === true) {
            $qb->where($qb->expr()->isNull('r.forkFrom'));
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case ('author'):
                        $qb->andWhere($qb->expr()->eq('r.author', "'" . $value . "'"));
                        break;
                    case ('owner'):
                        $qb->andWhere($qb->expr()->eq('r.owner', $value));
                        break;
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
                        if (!is_numeric($value)) {
                            throw new FilterException('public-except-user filter must be numeric');
                        }
                        $qb = $this->addPublicExceptUser($qb, $value);
                        break;
                }
            }

            // Metadata
            $i = 0;
            foreach ($metadata as $metaKey => $value) {
                $alias = 'm' . $i;
                $qb->leftJoin('r.metadata', $alias);

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
                $qb->leftJoin('r.metadata', $alias);

                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->eq($alias . '.key', "'" . $keyword . "'"),
                        $qb->expr()->like($alias . '.value', "'%" . $keyword . "%'")
                    )
                );

                $i++;
            }
            
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'author':
                        $qb->addOrderBy('r.author', $sort->getOrder());
                        break;
                    case 'type':
                        $qb->addOrderBy('r.type', $sort->getOrder());
                        break;
                    case 'id':
                        $qb->addOrderBy('r.id', $sort->getOrder());
                        break;
                }
            }
            $qb = $this->setRange($qb, $collectionInformation);
        } else {
            $qb->addOrderBy('r.id');
        }

        return new Paginator($qb);
    }

    /**
     * Add a required resource to a resource
     *
     * @param int              $resourceId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredResource($resourceId, ExerciseResource $requiredResource)
    {
        $sql = 'INSERT INTO claire_exercise_resource_resource_requirement VALUES (:resourceId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'resourceId' => $resourceId,
                    'requiredId' => $requiredResource->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required resource");
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int              $resourceId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredResource($resourceId, ExerciseResource $requiredResource)
    {
        $sql = 'DELETE FROM claire_exercise_resource_resource_requirement AS rrq WHERE rrq.resource_id = :resourceId AND rrq.required_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'resourceId' => $resourceId,
                'requiredId' => $requiredResource->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }

    /**
     * Add a required knowledge to an exercise model
     *
     * @param int              $resourceId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredKnowledge($resourceId, Knowledge $requiredKnowledge)
    {
        $sql = 'INSERT INTO claire_exercise_resource_knowledge_requirement VALUES (:resourceId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'resourceId' => $resourceId,
                    'requiredId'      => $requiredKnowledge->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required resource");
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int              $resourceId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredKnowledge($resourceId, Knowledge $requiredKnowledge)
    {
        $sql = 'DELETE FROM claire_exercise_resource_knowledge_requirement AS rrq WHERE rrq.resource_id = :resourceId AND rrq.required_knowledge_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'resourceId' => $resourceId,
                'requiredId'      => $requiredKnowledge->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }

    /**
     * Get all the exercise resources matching constraints
     *
     * @param ObjectConstraints $objectConstraints
     * @param User              $owner
     *
     * @return array An array of ExerciseResource which match the constraints
     */
    public function findResourcesFromConstraintsByOwner(
        ObjectConstraints $objectConstraints,
        User $owner
    )
    {
        $qb = $this->createQueryBuilder('r')
            ->select();

        $qb->where($qb->expr()->eq('r.owner', $owner->getId()));

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
            $qb->addGroupBy('r.id')
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
                'r.metadata',
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
                'r.public',
                'true'
            )
        );

        $notIn = $this->getEntityManager()->createQueryBuilder()
            ->select('re.id')
            ->from(
                'SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Resource',
                're'
            )
            ->andWhere(
                $qb->expr()->eq(
                    're.owner',
                    $userId
                )
            )
            ->getQuery()
            ->getDQL();

        $qb->andWhere($qb->expr()->notIn('r.id', $notIn));

        return $qb;
    }

    /**
     * Find a knowledge if it is owned by the user
     *
     * @param int     $resourceId
     * @param User $owner
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndOwner($resourceId, User $owner)
    {
        $queryBuilder = $this->createQueryBuilder('r');

        $queryBuilder->where($queryBuilder->expr()->eq('r.owner', $owner->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('r.id', $resourceId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find resource ' . $resourceId .
            ' for owner ' . $owner->getId());
        } else {
            return $result[0];
        }
    }
}
