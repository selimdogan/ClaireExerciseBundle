<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class ExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercises/{exerciseId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource';

    /**
     * Find an exercise
     *
     * @param int   $exerciseId Exercise Id
     * @param array $parameters Parameters
     *
     * @return ExerciseModelResource
     */
    public function find($exerciseId, array $parameters = array())
    {
        return $this->findResource(
            array('exerciseId' => $exerciseId),
            $parameters
        );
    }
}
