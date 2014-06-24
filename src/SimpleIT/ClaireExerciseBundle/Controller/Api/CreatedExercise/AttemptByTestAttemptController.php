<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API AttemptByTestAttempt Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptByTestAttemptController extends ApiController
{
    /**
     * List the attempts fot this exercise
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $testAttemptId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation, $testAttemptId)
    {
        try {
            $attempts = $this->get('simple_it.exercise.attempt')->getAll(
                $collectionInformation,
                $this->getUserIdIfNoCreator(),
                null,
                $testAttemptId
            );

            $attemptResources = AttemptResourceFactory::createCollection($attempts);

            return new ApiGotResponse($attemptResources, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }
}
