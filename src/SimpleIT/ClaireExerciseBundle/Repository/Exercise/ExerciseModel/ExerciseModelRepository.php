<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Model\Paginator;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\EntityAlreadyExistsException;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\FilterException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Sort;

/**
 * ExerciseModel repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelRepository extends BaseRepository
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
     * Find a list of exercise models according to a type an to metadata contained in a
     * collection information
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws FilterException
     * @return array
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        $qb = $this->createQueryBuilder('em')
            ->select();

        if ($collectionInformation !== null) {

            $filters = $collectionInformation->getFilters();
            foreach ($filters as $filter => $value) {
                switch ($filter) {
                    case ('type'):
                        $qb->where($qb->expr()->eq('em.type', "'" . $value . "'"));
                        break;
                    case ('authorId'):
                        $qb->where($qb->expr()->eq('em.author', "'" . $value . "'"));
                        break;
                    case ('draft'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('draft filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('em.draft', "'" . $value . "'"));
                        break;
                    case ('complete'):
                        if ($value !== "true" && $value !== "false") {
                            throw new FilterException('complete filter must be true or false');
                        }
                        $qb->where($qb->expr()->eq('em.complete', "'" . $value . "'"));
                        break;
                }
            }

            $sorts = $collectionInformation->getSorts();

            if (!empty($sorts)) {
                foreach ($sorts as $sort) {
                    /** @var Sort $sort */
                    switch ($sort->getProperty()) {
                        case 'title':
                            $qb->addOrderBy('em.title', $sort->getOrder());
                            break;
                        case 'id':
                            $qb->addOrderBy('em.id', $sort->getOrder());
                            break;
                    }
                }
                $qb = $this->setRange($qb, $collectionInformation);
            } else {
                $qb->addOrderBy('em.id');
            }
        }

        return new Paginator($qb);
    }

    /**
     * Add a required resource to an exercise model
     *
     * @param int              $exerciseModelId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityAlreadyExistsException
     */
    public function addRequiredResource($exerciseModelId, ExerciseResource $requiredResource)
    {
        $sql = 'INSERT INTO claire_exercise_model_resource_requirement VALUES (:exerciseModelId,:requiredId)';

        $connection = $this->_em->getConnection();
        try {
            $connection->executeQuery(
                $sql,
                array(
                    'exerciseModelId' => $exerciseModelId,
                    'requiredId'      => $requiredResource->getId(),
                )
            );
        } catch (DBALException $e) {
            throw new EntityAlreadyExistsException("Required resource");
        }
    }

    /**
     * Delete a requires resource
     *
     * @param int              $exerciseModelId
     * @param ExerciseResource $requiredResource
     *
     * @throws EntityDeletionException
     */
    public function deleteRequiredResource($exerciseModelId, ExerciseResource $requiredResource)
    {
        $sql = 'DELETE FROM claire_exercise_model_resource_requirement AS emrq WHERE emrq.model_id = :exerciseModelId AND emrq.required_resource_id = :requiredId';

        $connection = $this->_em->getConnection();
        $stmt = $connection->executeQuery(
            $sql,
            array(
                'exerciseModelId' => $exerciseModelId,
                'requiredId'      => $requiredResource->getId(),
            )
        );

        if ($stmt->rowCount() != 1) {
            throw new EntityDeletionException();
        }
    }
}
