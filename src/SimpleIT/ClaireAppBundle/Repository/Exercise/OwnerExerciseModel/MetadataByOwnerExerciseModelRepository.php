<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class MetadataByOwnerExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'owner-exercise-models/{ownerExerciseModelId}/metadata/{metaKey}';

    /**
     * @var string
     */
    protected $resourceClass = 'Doctrine\Common\Collections\ArrayCollection';

    /**
     * Insert a metadata
     *
     * @param int             $ownerExerciseModelId
     * @param ArrayCollection $metadata
     *
     * @return ResourceResource
     */
    public function insert($ownerExerciseModelId, ArrayCollection $metadata)
    {
        return $this->insertResource(
            $metadata,
            array('ownerExerciseModelId' => $ownerExerciseModelId)
        );
    }

    /**
     * Delete a metadata
     *
     * @param int $ownerExerciseModelId
     * @param int $metaKey
     */
    public function delete($ownerExerciseModelId, $metaKey)
    {
        $this->deleteResource(array('ownerExerciseModelId' => $ownerExerciseModelId, 'metaKey' => $metaKey));
    }

    /**
     * Update the list of metadata of a resource
     *
     * @param int             $ownerExerciseModelId
     * @param ArrayCollection $metadatas
     *
     * @return mixed
     */
    public function update($ownerExerciseModelId, ArrayCollection $metadatas)
    {
        return $this->updateResource($metadatas, array('ownerExerciseModelId' => $ownerExerciseModelId));
    }
}
