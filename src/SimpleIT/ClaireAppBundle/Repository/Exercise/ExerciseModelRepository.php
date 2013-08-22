<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class ExerciseModelRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercise-model/{exerciseModelId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource';

    /**
     * Find an exercise model
     *
     * @param int   $exerciseModelId Exercise Model Id
     * @param array $parameters      Parameters
     *
     * @return ExerciseModelResource
     */
    public function find($exerciseModelId, array $parameters = array())
    {
        return $this->findResource(
            array('exerciseModelId' => $exerciseModelId),
            $parameters
        );
    }
}
