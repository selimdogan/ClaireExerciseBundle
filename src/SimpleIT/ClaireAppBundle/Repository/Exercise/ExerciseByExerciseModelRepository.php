<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
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
     * @return ExerciseResource
     */
    public function generate($exerciseModelId, $parameters = array(), $format = null)
    {
        $request = $this->client->post(
            array($this->path, array('exerciseModelId' => $exerciseModelId)),
            null,
            null
        );

        $request = $this->prepareRequest($request, $parameters, $format);

//        throw new \Exception(print_r($request, true));
        $response = $request->send();

        $res =  $this->getResourceFromResponse($response);

        return $res;
    }
}
