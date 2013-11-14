<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel;

use SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class OwnerExerciseModelByExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelByExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercise-models/{exerciseModelId}/owner-exercise-models/{ownerExerciseModelId}';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource';

    /**
     * Find a list of ownerExerciseModels
     *
     * @param int $exerciseModelId
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll($exerciseModelId, CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(
            array('exerciseModelId' => $exerciseModelId),
            $collectionInformation
        );
    }

    /**
     * Insert a new owner exercise model
     *
     * @param OwnerExerciseModelResource $orr
     * @param int                   $exerciseModelId
     *
     * @return mixed
     */
    public function insert(OwnerExerciseModelResource $orr, $exerciseModelId)
    {
        return $this->insertResource($orr, array('exerciseModelId' => $exerciseModelId));
    }
}
