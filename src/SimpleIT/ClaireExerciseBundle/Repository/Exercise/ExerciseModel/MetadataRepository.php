<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataRepository extends BaseRepository
{
    /**
     * Find a model by id
     *
     * @param array $parameters
     *
     * @return Metadata
     * @throws NonExistingObjectException
     */
    public function find($parameters)
    {
        $metadata = parent::find($parameters);
        if ($metadata === null) {
            throw new NonExistingObjectException();
        }

        return $metadata;
    }

    /**
     * Return all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param ExerciseModel    $exerciseModel
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $exerciseModel = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if (!is_null($exerciseModel)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'm.exerciseModel',
                    $exerciseModel->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case 'exerciseModelId':
                        $queryBuilder->addOrderBy('m.exerciseModel', $sort->getOrder());
                        break;
                    case 'key':
                        $queryBuilder->addOrderBy('m.key', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('m.exerciseModel', 'ASC');
            $queryBuilder->addOrderBy('m.key', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Delete all the metadata for an exercise model
     *
     * @param int $exerciseModelId
     */
    public function deleteAllByExerciseModel($exerciseModelId)
    {
        $sql = 'DELETE FROM claire_exercise_model_metadata AS emm WHERE emm.exercise_model_id = :exerciseModelId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('exerciseModelId' => $exerciseModelId)
        );
    }
}
