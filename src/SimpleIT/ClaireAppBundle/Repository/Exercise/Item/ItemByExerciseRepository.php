<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Item;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\PaginatedCollection;

/**
 * Class ExerciseByExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemByExerciseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercises/{exerciseId}/items/{itemId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ItemResource';

    /**
     * Find all the items of an exercise
     *
     * @param int $exerciseId
     *
     * @return PaginatedCollection
     */
    public function findAll($exerciseId)
    {
        return $this->findAllResources(array('exerciseId' => $exerciseId));
    }
}
