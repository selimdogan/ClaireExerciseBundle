<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\OwnerResource;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\Utils\FormatUtils;

/**
 * Class OwnerResourceByOwnerRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByOwnerRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'users/{userId}/owner-resources/{ownerResourceId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource';

    /**
     * Find a list of ownerResources
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
