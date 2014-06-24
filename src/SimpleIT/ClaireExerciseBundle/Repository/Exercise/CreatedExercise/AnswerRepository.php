<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;

/**
 * Answer repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerRepository extends BaseRepository
{
    /**
     * Find an answer by id
     *
     * @param int $itemId
     *
     * @return Answer
     * @throws NonExistingObjectException
     */
    public function find($itemId)
    {
        $item = parent::find($itemId);
        if ($item === null) {
            throw new NonExistingObjectException();
        }

        return $item;
    }

    /**
     * Get all the answers. An item can be specified.
     *
     * @param Item    $item
     * @param Attempt $attempt
     *
     * @return array
     */
    public function findAllBy($item = null, $attempt = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if (!is_null($item)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.item',
                    $item->getId()
                )
            );
        }

        if (!is_null($attempt)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.attempt',
                    $attempt->getId()
                )
            );
        }

        $queryBuilder->add('orderBy', 'a.id', true);

        return $queryBuilder->getQuery()->getResult();
    }
}
