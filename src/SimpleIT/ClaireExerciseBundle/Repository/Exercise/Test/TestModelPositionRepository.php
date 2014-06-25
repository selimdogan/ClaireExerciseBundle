<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\Test;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModel;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;

/**
 * Class TestModelPositionRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelPositionRepository extends BaseRepository
{
    /**
     * Delete all the positions for a test model
     *
     * @param TestModel $testModel
     */
    public function deleteAllPositions($testModel)
    {
        if (count($testModel->getTestModelPositions()) > 0) {
            $qb = $this->createQueryBuilder('tmp');
            $qb->delete(
                get_class($testModel->getTestModelPositions()[0]),
                'tmp'
            );
            $qb->where($qb->expr()->eq('tmp.testModel', $testModel->getId()));
            $qb->getQuery()->getResult();
        }
    }
}
