<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Controller\BaseController;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Api\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * API Exercise controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseController extends BaseController
{
    /**
     * View a stored exercise
     *
     * @param int $exerciseId Exercise id
     *
     * @return ApiGotResponse
     * @throws ApiNotFoundException
     */
    public function viewAction($exerciseId)
    {
        try {
            $exercise = $this->get('simple_it.exercise.stored_exercise')->get($exerciseId);
            $exerciseResource = ExerciseResourceFactory::create($exercise);

            return new ApiGotResponse($exerciseResource, array("exercise", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all the exercises
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\Api\ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {
            if (!$this->get('security.context')->getToken()->getUser()->hasRole('ROLE_WS_CREATOR'))
            {
                throw new AccessDeniedException();
            }

            $exercises = $this->get('simple_it.exercise.stored_exercise')->getAll(
                $collectionInformation
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
