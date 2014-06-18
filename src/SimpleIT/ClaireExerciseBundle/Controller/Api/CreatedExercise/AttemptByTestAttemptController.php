<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
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
     * @return ApiGotResponse
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

            return new ApiGotResponse($attemptResources, array('list', 'Default'));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AnswerResource::RESOURCE_NAME);
        }
    }
}
