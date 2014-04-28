<?php

namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Model\Resources\ExerciseResourceFactory;

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
     * @return ApiPaginatedResponse
     * @throws ApiNotFoundException
     */
    public function listAction($testAttemptId)
    {
        try {
            $exercises = $this->get('simple_it.exercise.stored_exercise')->getAllByTestAttempt(
                $testAttemptId
            );

            $exerciseResources = ExerciseResourceFactory::createCollection($exercises);

            return new ApiPaginatedResponse($exerciseResources, $exercises, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseResource::RESOURCE_NAME);
        }
    }
}
