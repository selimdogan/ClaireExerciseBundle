<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource;

use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\Utils\FormatUtils;

/**
 * Class OwnerResourceByOwnerRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByResourceRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'resources/{resourceId}/owner-resources/{ownerResourceId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource';

    /**
     * Find a list of ownerResources
     *
     * @param int                   $resourceId
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll($resourceId, CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(array('resourceId' => $resourceId), $collectionInformation);
    }

    /**
     * Insert a new owner resource
     *
     * @param OwnerResourceResource $orr
     * @param int                   $resourceId
     *
     * @return mixed
     */
    public function insert(OwnerResourceResource $orr, $resourceId)
    {
        return $this->insertResource($orr, array('resourceId' => $resourceId));
    }
}
