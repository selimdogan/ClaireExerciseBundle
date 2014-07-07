<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiBadRequestException;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AttemptResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Attempt controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptController extends BaseController
{
    /**
     * Get a specific Attempt resource
     *
     * @param int $attemptId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($attemptId)
    {
        try {
            $attempt = $this->get('simple_it.exercise.attempt')->get(
                $attemptId,
                $this->getUserId()
            );
            $attemptResource = AttemptResourceFactory::create($attempt);

            return new ApiGotResponse($attemptResource, array("attempt", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(AttemptResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of attempts
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiBadRequestException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $attempts = $this->get('simple_it.exercise.attempt')->getAll(
            $collectionInformation,
            $this->getUserIdIfNoCreator()
        );

        $attemptsResources = AttemptResourceFactory::createCollection($attempts);

        return new ApiGotResponse($attemptsResources, array('list', 'Default'));
    }
}
