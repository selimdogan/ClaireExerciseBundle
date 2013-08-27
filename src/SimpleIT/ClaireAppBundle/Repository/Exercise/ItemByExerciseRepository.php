<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\AppBundle\Repository\AppRepository;

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
    protected $path = 'exercise/{exerciseId}/items/{itemId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ItemResource';

}
