<?php

namespace SimpleIT\ExerciseBundle\Repository\CreatedExercise;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Item;

/**
 * Answer repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerRepository extends BaseRepository
{
    /**
     * Get all the answers. An item can be specified.
     *
     * @param Item    $item
     * @param Attempt $attempt
     *
     * @return Paginator
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

        return new Paginator($queryBuilder);
    }
}
