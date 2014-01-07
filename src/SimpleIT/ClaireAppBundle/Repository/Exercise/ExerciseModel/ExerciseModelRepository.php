<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModel;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class ExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercise-models/{exerciseModelId}';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource';

    /**
     * Find an exercise model to edit
     *
     * @param string $exerciseModelId       Exercise model id
     * @param array  $parameters            Parameters
     *
     * @return ExerciseModelResource
     */
    public function findToEdit($exerciseModelId, array $parameters = array())
    {
        return $this->findResource(
            array('exerciseModelId' => $exerciseModelId),
            $parameters
        );
    }

    /**
     * Find an exercise model
     *
     * @param string $exerciseModelId       Exercise model id
     * @param array  $parameters            Parameters
     *
     * @return ExerciseModelResource
     */
    public function find($exerciseModelId, array $parameters = array())
    {
        return $this->findResource(
            array('exerciseModelId' => $exerciseModelId),
            $parameters
        );
    }

    /**
     * Update an exercise model
     *
     * @param string                $exerciseModelId               Exercise model id
     * @param ExerciseModelResource $exerciseModel                 Exercise model
     * @param array                 $parameters                    Parameters
     *
     * @return ExerciseModelResource
     */
    public function update(
        $exerciseModelId,
        ExerciseModelResource $exerciseModel,
        array $parameters = array()
    )
    {
        return $this->updateResource(
            $exerciseModel,
            array('exerciseModelId' => $exerciseModelId),
            $parameters
        );
    }

    /**
     * Insert an exercise model
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return ExerciseModelResource
     */
    public function insert(ExerciseModelResource $exerciseModel)
    {
        return $this->insertResource($exerciseModel);
    }

    /**
     * Delete an exercise resource
     *
     * @param $exerciseModelId
     */
    public function delete($exerciseModelId)
    {
        $this->deleteResource(array('exerciseModelId' => $exerciseModelId));
    }

    /**
     * Find all the exercise models
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function findAll($collectionInformation = null)
    {
        return $this->findAllResources(array(), $collectionInformation);
    }
}
