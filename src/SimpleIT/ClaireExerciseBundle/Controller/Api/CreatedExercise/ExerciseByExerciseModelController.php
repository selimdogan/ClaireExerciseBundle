<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Api\CreatedExercise;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class ExerciseByExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseByExerciseModelController extends ApiController
{
    /**
     * Generate an exercise from the model id
     *
     * @param int $exerciseModelId Exercise Model Id
     *
     * @throws ApiNotFoundException
     * @return ApiCreatedResponse
     */
    public function createAction($exerciseModelId)
    {
        try {
            $exercise = $this->get('simple_it.exercise.stored_exercise')->addByExerciseModel(
                $exerciseModelId
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
     * @param int                   $exerciseModelId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation, $exerciseModelId)
    {
        try {
            $exercises = $this->get('simple_it.exercise.stored_exercise')->getAll
                (
                    $collectionInformation,
                    $exerciseModelId
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
