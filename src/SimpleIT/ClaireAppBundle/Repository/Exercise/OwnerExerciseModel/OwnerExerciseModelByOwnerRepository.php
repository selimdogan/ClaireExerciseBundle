<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class OwnerExerciseModelByOwnerRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelByOwnerRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'users/{userId}/owner-exercise-models/{ownerExerciseModelId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource';

    /**
     * Find a list of ownerExerciseModels
     *
     * @param int                   $userId
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll($userId, CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(array('userId' => $userId), $collectionInformation);
    }
}
