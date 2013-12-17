<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel;

use SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\Utils\FormatUtils;

/**
 * Class OwnerExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'owner-exercise-models/{ownerExerciseModelId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource';

    /**
     * Find a list of ownerExerciseModels
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(array(), $collectionInformation);
    }

    /**
     * Find an ownerExerciseModel
     *
     * @param string $ownerExerciseModelId
     * @param array  $parameters
     *
     * @return OwnerExerciseModelResource
     */
    public function find($ownerExerciseModelId, array $parameters = array())
    {
        return $this->findResource(
            array('ownerExerciseModelId' => $ownerExerciseModelId),
            $parameters
        );
    }

    /**
     * Update a part content
     *
     * @param int                        $ownerExerciseModelId
     * @param OwnerExerciseModelResource $ownerExerciseModel
     * @param array                      $parameters
     * @param string                     $format
     *
     * @return string
     */
    public function update(
        $ownerExerciseModelId,
        OwnerExerciseModelResource $ownerExerciseModel,
        $parameters = array(),
        $format = FormatUtils::JSON
    )
    {
        return parent::updateResource(
            $ownerExerciseModel,
            array('ownerExerciseModelId' => $ownerExerciseModelId),
            $parameters,
            $format
        );
    }

    /**
     * Insert a new ownerExerciseModel
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     *
     * @return OwnerExerciseModelResource
     */
    public function insert(OwnerExerciseModelResource $ownerExerciseModelResource)
    {
        return $this->insertResource($ownerExerciseModelResource);
    }
}
