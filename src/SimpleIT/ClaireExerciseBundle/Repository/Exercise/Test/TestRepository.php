<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\Test;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModel;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * Class TestRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestRepository extends BaseRepository
{
    /**
     * Find all tests
     *
     * @param CollectionInformation $collectionInformation
     * @param TestModel             $testModel
     *
     * @return Paginator
     */
    public function findAllBy($collectionInformation = null, $testModel = null)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        // Handle Collection Information
        if (!is_null($testModel)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    't.testModel',
                    $testModel->getId()
                )
            );
        }
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'id':
                        $queryBuilder->addOrderBy('t.id', $sort->getOrder());
                        break;
                    default:
                        $queryBuilder->addOrderBy('t.id');
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        }

        return new Paginator($queryBuilder);
    }

    /**
     * Find a test by id
     *
     * @param mixed $testAttemptId
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
