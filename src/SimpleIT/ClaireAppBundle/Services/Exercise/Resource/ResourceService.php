<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Resource;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\OwnerResourceRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\ResourceRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class ResourceService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceService
{
    /**
     * @var  ResourceRepository
     */
    private $resourceRepository;

    /**
     * Set resourceRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\ResourceRepository $resourceRepository
     */
    public function setResourceRepository($resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @param int   $resourceId   Resource id
     * @param array $parameters   Parameters
     *
     * @return ResourceResource
     */
    public function getResourceToEdit($resourceId, array $parameters = array())
    {
        return $this->resourceRepository->findToEdit($resourceId, $parameters);
    }

    /**
     * Save a resource
     *
     * @param int              $resourceId Resource id
     * @param ResourceResource $resource
     * @param array            $parameters
     *
     * @return ResourceResource
     */
    public function save($resourceId, ResourceResource $resource, array $parameters = array())
    {
        return $this->resourceRepository->update($resourceId, $resource, $parameters);
    }
}
