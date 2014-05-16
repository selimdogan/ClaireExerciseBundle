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
 * Class TestModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelRepository extends BaseRepository
{
    /**
     * Find a test model by its id
     *
     * @param mixed $testModelId
     *
     * @return TestModel
     * @throws NonExistingObjectException
     */
    public function find($testModelId)
    {
        $resource = parent::find($testModelId);
        if ($resource === null) {
            throw new NonExistingObjectException();
        }

        return $resource;
    }

    /**
     * Find all
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return Paginator
     */
    public function findAllBy($collectionInformation = null)
    {
        $queryBuilder = $this->createQueryBuilder('tm');

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'id':
                        $queryBuilder->addOrderBy('tm.id', $sort->getOrder());
                        break;
                    case 'title':
                        $queryBuilder->addOrderBy('t.title', $sort->getOrder());
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
     * Delete all the positions for a test model
     *
     * @param int $testModelId
     */
    public function deleteAllPositions($testModelId)
    {
        $sql = 'DELETE FROM claire_exercise_test_model_position AS etmp WHERE etmp.test_model_id = :testModelId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('testModelId' => $testModelId)
        );
    }
}
