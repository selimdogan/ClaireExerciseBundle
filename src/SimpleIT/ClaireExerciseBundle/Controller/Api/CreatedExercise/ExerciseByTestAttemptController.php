<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResourceFactory;

/**
 * Class ExerciseByTestAttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseByTestAttemptController extends ApiController
{
    /**
     * List all the Exercises for a test attempt
     *
     * @param $testAttemptId
     *
     * @return ApiGotResponse
     * @throws ApiNotFoundException
     */
    public function listAction($testAttemptId)
    {
        try {
            $exercises = $this->get('simple_it.exercise.stored_exercise')->getAllByTestAttempt(
                $testAttemptId
            );

            $exerciseResources = ExerciseResourceFactory::createCollection($exercises);

            return new ApiGotResponse($exerciseResources, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseResource::RESOURCE_NAME);
        }
    }
}
