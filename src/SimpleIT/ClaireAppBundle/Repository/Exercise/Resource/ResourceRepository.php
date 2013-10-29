<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Resource;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\AppBundle\Services\ApiService;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Model\AuthorFactory;
use SimpleIT\ClaireAppBundle\Model\CategoryFactory;
use SimpleIT\ClaireAppBundle\Model\CourseFactory;
use SimpleIT\ClaireAppBundle\Model\MetadataFactory;
use SimpleIT\ClaireAppBundle\Model\TagFactory;
use SimpleIT\ClaireAppBundle\Model\TocFactory;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\AppBundle\Annotation\CacheInvalidation;
use SimpleIT\Utils\FormatUtils;

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
     * @return CourseResource
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
