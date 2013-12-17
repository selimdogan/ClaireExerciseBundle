<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class MetadataByOwnerResourceRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerResourceRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'owner-resources/{ownerResourceId}/metadata/{metaKey}';

    /**
     * @var string
     */
    protected $resourceClass = 'Doctrine\Common\Collections\ArrayCollection';

    /**
     * Insert a metadata
     *
     * @param int             $ownerResourceId
     * @param ArrayCollection $metadata
     *
     * @return ResourceResource
     */
    public function insert($ownerResourceId, ArrayCollection $metadata)
    {
        return $this->insertResource(
            $metadata,
            array('ownerResourceId' => $ownerResourceId)
        );
    }

    /**
     * Delete a metadata
     *
     * @param int $ownerResourceId
     * @param int $metaKey
     */
    public function delete($ownerResourceId, $metaKey)
    {
        $this->deleteResource(array('ownerResourceId' => $ownerResourceId, 'metaKey' => $metaKey));
    }

    /**
     * Update the list of metadata of a resource
     *
     * @param int             $ownerResourceId
     * @param ArrayCollection $metadatas
     *
     * @return mixed
     */
    public function update($ownerResourceId, ArrayCollection $metadatas)
    {
        return $this->updateResource($metadatas, array('ownerResourceId' => $ownerResourceId));
    }
}
