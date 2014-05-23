<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\MetadataConstraint;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * ExerciseResource repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResourceRepository extends SharedEntityRepository
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
     * Add a required resource to a resource
     *
     * @param int              $resourceId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredResource($resourceId, ExerciseResource $requiredResource)
    {
        parent::addRequired(
            $resourceId,
            $requiredResource,
            'claire_exercise_resource_resource_requirement',
            'resource'
        );
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
        parent::deleteRequired(
            $resourceId,
            $requiredResource,
            'claire_exercise_resource_resource_requirement',
            'resource_id',
            'required_id'
        );
    }

    /**
     * Add a required knowledge to an exercise model
     *
     * @param int       $resourceId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredKnowledge($resourceId, Knowledge $requiredKnowledge)
    {
        parent::addRequired(
            $resourceId,
            $requiredKnowledge,
            'claire_exercise_resource_knowledge_requirement',
            'knowledge'
        );
    }

    /**
     * Delete a requires resource
     *
     * @param int       $resourceId
     * @param Knowledge $requiredKnowledge
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredKnowledge($resourceId, Knowledge $requiredKnowledge)
    {
        parent::deleteRequired(
            $resourceId,
            $requiredKnowledge,
            'claire_exercise_resource_knowledge_requirement',
            'resource_id',
            'required_knowledge_id'
        );
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
     * Find a knowledge if it is owned by the user
     *
     * @param int  $entityId
     * @param User $owner
     *
     * @return mixed
     * @throws NonExistingObjectException
     */
    public function findByIdAndOwner($entityId, User $owner)
    {
        $queryBuilder = $this->createQueryBuilder('r');

        $queryBuilder->where($queryBuilder->expr()->eq('r.owner', $owner->getId()));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('r.id', $entityId));

        $result = $queryBuilder->getQuery()->getResult();

        if (empty($result)) {
            throw new NonExistingObjectException('Unable to find resource ' . $entityId .
            ' for owner ' . $owner->getId());
        } else {
            return $result[0];
        }
    }
}
