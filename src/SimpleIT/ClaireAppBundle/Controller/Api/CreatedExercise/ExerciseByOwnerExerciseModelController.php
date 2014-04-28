<?php

namespace SimpleIT\ExerciseBundle\Controller\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Model\Resources\ExerciseResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class ExerciseByExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseByOwnerExerciseModelController extends ApiController
{
    /**
     * Generate an exercise from the owner model id
     *
     * @param int $ownerExerciseModelId Exercise Model Id
     *
     * @throws ApiNotFoundException
     * @return ApiCreatedResponse
     */
    public function createAction($ownerExerciseModelId)
    {
        try {
            $exercise = $this->get('simple_it.exercise.stored_exercise')->addByOwnerExerciseModel(
                $ownerExerciseModelId
            );
            $exerciseResource = ExerciseResourceFactory::create($exercise);

            return new ApiCreatedResponse($exerciseResource, array('exercise', 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException('Resource');
        }
    }

    /**
     * List the stored exercises of this model
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerExerciseModelId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation, $ownerExerciseModelId)
    {
        try {
            $exercises = $this->get('simple_it.exercise.stored_exercise')->getAll
                (
                    $collectionInformation,
                    $ownerExerciseModelId
                );

            $exerciseResources = ExerciseResourceFactory::createCollection($exercises);

            return new ApiPaginatedResponse($exerciseResources, $exercises, array(
                'list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ExerciseModelResource::RESOURCE_NAME);
        }
    }
}
