<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource;

use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\Utils\FormatUtils;

/**
 * Class OwnerResourceRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'owner-resources/{ownerResourceId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource';

    /**
     * Find a list of ownerResources
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
     * Find an ownerResource
     *
     * @param string $ownerResourceId
     * @param array  $parameters
     *
     * @return OwnerResourceResource
     */
    public function find($ownerResourceId, array $parameters = array())
    {
        return $this->findResource(
            array('ownerResourceId' => $ownerResourceId),
            $parameters
        );
    }

    /**
     * Update a part content
     *
     * @param int                   $ownerResourceId
     * @param OwnerResourceResource $ownerResource
     * @param array                 $parameters
     * @param string                $format
     *
     * @return string
     */
    public function update(
        $ownerResourceId,
        OwnerResourceResource $ownerResource,
        $parameters = array(),
        $format = FormatUtils::JSON
    )
    {
        return parent::updateResource(
            $ownerResource,
            array('ownerResourceId' => $ownerResourceId),
            $parameters,
            $format
        );
    }

    /**
     * Insert a new ownerResource
     *
     * @param OwnerResourceResource $ownerResourceResource
     *
     * @return OwnerResourceResource
     */
    public function insert(OwnerResourceResource $ownerResourceResource)
    {
        return $this->insertResource($ownerResourceResource);
    }
}
