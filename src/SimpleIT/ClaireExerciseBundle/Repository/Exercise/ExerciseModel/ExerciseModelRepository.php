<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;

/**
 * ExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelRepository extends SharedEntityRepository
{
    /**
     * Find a model by id
     *
     * @param mixed $exerciseModelId
     *
     * @return ExerciseModel
     * @throws NonExistingObjectException
     */
    public function find($exerciseModelId)
    {
        $exerciseModel = parent::find($exerciseModelId);
        if ($exerciseModel === null) {
            throw new NonExistingObjectException();
        }

        return $exerciseModel;
    }

    /**
     * Find the models attempted by the user, and the answers
     *
     * @param $userId
     *
     * @return array
     */
    public function findAllByUserWhoAttempted($userId)
    {
        $qb = $this->createQueryBuilder('em')
            ->select('em, e, at, an');

        $qb->leftJoin('em.exercises', 'e');
        $qb->leftJoin('e.attempts', 'at');
        $qb->leftJoin('at.answers', 'an');

        $qb->where($qb->expr()->eq('at.user', $userId));

        $qb->addOrderBy('em.id');

        return $qb->getQuery()->getResult();
    }

    /**
     * Find the models attempted by the user, and the answers
     *
     * @param $userId
     * @param $modelId
     *
     * @return array
     */
    public function findAllByUserWhoAttemptedByModel($userId, $modelId)
    {
        $qb = $this->createQueryBuilder('em')
            ->select('em, e, at, an');

        $qb->leftJoin('em.exercises', 'e');
        $qb->leftJoin('e.attempts', 'at');
        $qb->leftJoin('at.answers', 'an');

        $qb->andWhere($qb->expr()->eq('em.id', $modelId));
        $qb->where($qb->expr()->eq('at.user', $userId));

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Get the join that reduce the number of requests.
     *
     * @return array
     */
    protected function getLeftJoins()
    {
        return array(
            "rr" => "entity.requiredExerciseResources",
            "rk" => "entity.requiredKnowledges"
        );
    }
}
