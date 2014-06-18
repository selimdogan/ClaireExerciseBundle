<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\Utils\Collection\CollectionInformation;

use SimpleIT\Utils\Collection\Sort;

/**
 * StoredExercise repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class StoredExerciseRepository extends BaseRepository
{
    /**
     * Return all the stored exercises corresponding to an exercise model (if specified)
     *
     * @param CollectionInformation $collectionInformation
     * @param ExerciseModel    $exerciseModel
     *
     * @return array
     */
    public function findAllBy(
        $collectionInformation = null,
        $exerciseModel = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('se');

        if (!is_null($exerciseModel)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'se.exerciseModel',
                    $exerciseModel->getId()
                )
            );
        }

        $queryBuilder->addOrderBy('se.id');

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case 'exerciseModelId':
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->eq(
                                'se.exerciseModel',
                                $value
                            )
                        );
                        break;
                    default:
                        break;
                }
            }
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'title':
                        $queryBuilder->addOrderBy('se.title', $sort->getOrder());
                        break;
                    case 'id':
                        $queryBuilder->addOrderBy('se.id', $sort->getOrder());
                        break;
                }
            }
            // FIXME wait for a fix in api-bundle
//            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        }

        return $queryBuilder->getQuery()->getResult();

    }

    /**
     * Get all the exercises by test attempt Id
     *
     * @param TestAttempt $testAttempt
     *
     * @return array
     */
    public function findAllByTestAttempt(TestAttempt $testAttempt)
    {
        $queryBuilder = $this->createQueryBuilder('se');
        $queryBuilder->leftJoin('se.testPositions', 'tp');
        $queryBuilder->leftJoin('tp.test', 't');
        $queryBuilder->leftJoin('t.testAttempts', 'ta');

        $queryBuilder->where($queryBuilder->expr()->eq('ta.id', $testAttempt->getId()));
        $queryBuilder->orderBy('tp.position');

        return $queryBuilder->getQuery()->getResult();
    }
}
