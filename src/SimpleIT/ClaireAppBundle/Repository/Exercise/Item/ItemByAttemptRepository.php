<?php
namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Item;

use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class ItemByAttempt
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemByAttemptRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'attempts/{attemptId}/items/{itemId}';

    /**
     * @var string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ItemResource';

    /**
     * Find a list of items
     *
     * @param int                   $attemptId
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll($attemptId, CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(
            array('attemptId' => $attemptId),
            $collectionInformation
        );
    }

    /**
     * Find an item in the attempt
     *
     * @param int $attemptId
     * @param int $itemId
     *
     * @return ItemResource
     */
    public function find($attemptId, $itemId)
    {
        return $this->findResource(array('attemptId' => $attemptId, 'itemId' => $itemId));
    }
}
