<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class ExerciseByOwnerExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseByOwnerExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'owner-exercise-models/{ownerExerciseModelId}/exercises/{exerciseId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource';

    /**
     * Generate an exercise from an owner exercise model id
     *
     * @param int   $ownerExerciseModelId
     * @param array $parameters
     * @param null  $format
     *
     * @return ExerciseResource
     */
    public function generate($ownerExerciseModelId, $parameters = array(), $format = null)
    {
        $request = $this->client->post(
            array($this->path, array('ownerExerciseModelId' => $ownerExerciseModelId)),
            null,
            null
        );

        return $this->getSingleResource($request, $parameters, $format);
    }
}
