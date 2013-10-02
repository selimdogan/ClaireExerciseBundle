<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ItemResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class ItemRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'items/{itemId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ItemResource';

    /**
     * Find an item
     *
     * @param int   $itemId     Item id
     * @param array $parameters Parameters
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     * @return ItemResource
     */
    public function find($itemId, array $parameters = array())
    {
        $item = $this->findResource(
            array('itemId' => $itemId),
            $parameters
        );

        if ($item === null) {
            throw new ResourceNotFoundException("Item not existing");
        }

        return $item;
    }
}
