<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\Test;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\Test\Test;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use SimpleIT\ClaireExerciseBundle\Model\Collection\Sort;

/**
 * TestAttempt Repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptRepository extends BaseRepository
{
    /**
     * Find all test attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $userId
     * @param Test                  $test
     *
     * @return array
     */
    public function findAllBy(
        $collectionInformation = null,
        $userId = null,
        $test = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('ta');

        if (!is_null($userId)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'ta.user',
                    $userId
                )
            );
        }

        if (!is_null($test)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'ta.test',
                    $test->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'testId':
                        $queryBuilder->addOrderBy('ta.test', $sort->getOrder());
                        break;
                    case 'userId':
                        $queryBuilder->addOrderBy('ta.user', $sort->getOrder());
                        break;
                    case 'id':
                        $queryBuilder->addOrderBy('ta.id', $sort->getOrder());
                        break;
                    default:
                        $queryBuilder->addOrderBy('ta.id');
                        break;
                }
            }
            // FIXME wait for a fix in api-bundle
//            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Find a test attempt by id
     *
     * @param int $testAttemptId
     *
     * @return object
     * @throws NonExistingObjectException
     */
    public function find($testAttemptId)
    {
        $test = parent::find($testAttemptId);
        if ($test === null) {
            throw new NonExistingObjectException();
        }

        return $test;
    }
}
