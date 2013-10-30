<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Resource;

use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class ResourceRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'resources/{resourceId}';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ResourceResource';

    /**
     * Find a resource to edit
     *
     * @param string $resourceId       Resource id
     * @param array  $parameters       Parameters
     *
     * @return ResourceResource
     */
    public function findToEdit($resourceId, array $parameters = array())
    {
        return $this->findResource(
            array('resourceId' => $resourceId),
            $parameters
        );
    }

    /**
     * Update a resource
     *
     * @param string           $resourceId         Resource id
     * @param ResourceResource $resource           Resource
     * @param array            $parameters         Parameters
     *
     * @return ResourceResource
     */
    public function update($resourceId, ResourceResource $resource, array $parameters = array())
    {
        return $this->updateResource(
            $resource,
            array('resourceId' => $resourceId),
            $parameters
        );
    }
}
