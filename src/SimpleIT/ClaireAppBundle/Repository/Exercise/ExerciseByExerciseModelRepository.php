<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class ExerciseByExerciseModelRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseByExerciseModelRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'exercise-models/{exerciseModelId}/exercises/{exerciseId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource';

    /**
     * Generate an exercise from an exercise model id
     *
     * @param int   $exerciseModelId
     * @param array $parameters
     * @param null  $format
     *
     * @return mixed
     */
    public function generate($exerciseModelId, $parameters = array(), $format = null)
    {
        $request = $this->client->post(
            array($this->path, parent::formatIdentifiers($exerciseModelId)),
            null,
            null
        );

        return $this->getSingleResource($request, $parameters, $format);
    }
}
