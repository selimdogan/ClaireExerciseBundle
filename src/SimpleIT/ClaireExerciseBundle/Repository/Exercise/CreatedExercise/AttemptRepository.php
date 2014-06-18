<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * Attempt Repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptRepository extends BaseRepository
{
    /**
     * Return all the attempts
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $userId
     * @param StoredExercise        $exercise
     * @param TestAttempt           $testAttempt
     *
     * @return array
     */
    public function findAllBy(
        $collectionInformation = null,
        $userId = null,
        $exercise = null,
        $testAttempt = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if (!is_null($exercise)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.exercise',
                    $exercise->getId()
                )
            );
        }

        if (!is_null($testAttempt)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.testAttempt',
                    $testAttempt->getId()
                )
            );
        }

        if (!is_null($userId)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.user',
                    $userId
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case 'userId':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'a.user',
                                $value
                            )
                        );
                        break;
                    case 'testAttemptId':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'a.testAttempt',
                                $value
                            )
                        );
                        break;
                    default:
                        break;
                }
            }
            $sorts = $collectionInformation->getSorts();

            if (count($sorts) > 0) {
                foreach ($sorts as $sort) {
                    /** @var Sort $sort */
                    switch ($sort->getProperty()) {
                        case 'userId':
                            $queryBuilder->addOrderBy('a.user', $sort->getOrder());
                            break;
                        case 'testAttemptId':
                            $queryBuilder->addOrderBy('a.testAttempt', $sort->getOrder());
                            break;
                        case 'exerciseId':
                            $queryBuilder->addOrderBy('a.exercise', $sort->getOrder());
                            break;
                        case 'id':
                            $queryBuilder->addOrderBy('a.id', $sort->getOrder());
                            break;
                    }
                }
            } else {
                if (!is_null($testAttempt)) {
                    $queryBuilder->addOrderBy('a.position');
                } else {
                    $queryBuilder->addOrderBy('a.id');
                }
            }
        } else {
            $queryBuilder->addOrderBy('a.id');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
