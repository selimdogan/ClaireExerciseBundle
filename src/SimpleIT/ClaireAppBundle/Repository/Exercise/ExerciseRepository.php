<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     * @return ExerciseModelResource
     */
    public function find($exerciseId, array $parameters = array())
    {
        $exercise = $this->findResource(
            array('exerciseId' => $exerciseId),
            $parameters
        );

        if ($exercise === null) {
            throw new ResourceNotFoundException("Exercise not existing");
        }

        return $exercise;
    }
}
