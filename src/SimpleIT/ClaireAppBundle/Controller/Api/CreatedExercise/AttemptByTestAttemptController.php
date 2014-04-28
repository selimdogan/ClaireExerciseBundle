<?php
namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
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
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation, $testAttemptId)
    {
        try {
            $attempts = $this->get('simple_it.exercise.attempt')->getAll(
                $collectionInformation,
                null,
                null,
                $testAttemptId
            );

            $attemptResources = AttemptResourceFactory::createCollection($attempts);

            return new ApiPaginatedResponse($attemptResources, $attempts, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }
}
