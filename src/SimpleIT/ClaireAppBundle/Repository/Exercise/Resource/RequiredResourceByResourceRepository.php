<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class RequiredResourceRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByResourceRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'resources/{resourceId}/required-resources/{reqResId}';

    /**
     * @var string
     */
    protected $resourceClass = 'Doctrine\Common\Collections\ArrayCollection';

    /**
     * Insert a required resource
     *
     * @param int $resourceId
     * @param int $reqResId
     *
     * @return ResourceResource
     */
    public function insert($resourceId, $reqResId)
    {
        return $this->insertResource(
            null,
            array('resourceId' => $resourceId, 'reqResId' => $reqResId)
        );
    }

    /**
     * Delete a required resource
     *
     * @param int $resourceId
     * @param int $reqResId
     */
    public function delete($resourceId, $reqResId)
    {
        $this->deleteResource(array('resourceId' => $resourceId, 'reqResId' => $reqResId));
    }

    /**
     * Update the list of required resources of a resource
     *
     * @param int             $resourceId
     * @param ArrayCollection $requiredResources
     *
     * @return mixed
     */
    public function update($resourceId, ArrayCollection $requiredResources)
    {
        return $this->updateResource($requiredResources, array('resourceId' => $resourceId));
    }
}
