<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface for service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ExerciseModelServiceInterface
{
    /**
     * Get an Exercise Model entity
     *
     * @param int $exerciseModelId
     *
     * @return ExerciseModel
     * @throws NonExistingObjectException
     */
    public function get($exerciseModelId);

    /**
     * Get an exercise Model (business object, no entity)
     *
     * @param int $exerciseModelId
     *
     * @return object
     * @throws \LogicException
     */
    public function getModel($exerciseModelId);

    /**
     * Get an exercise model from an entity
     *
     * @param ExerciseModel $entity
     *
     * @return CommonModel
     * @throws \LogicException
     */
    public function getModelFromEntity(ExerciseModel $entity);

    /**
     * Get a list of Exercise Model
     *
     * @param CollectionInformation $collectionInformation The collection information
     *
     * @return PaginatorInterface
     */
    public function getAll($collectionInformation = null);

    /**
     * Create an ExerciseModel entity from a resource
     *
     * @param ExerciseModelResource $modelResource
     * @param int                   $authorId
     *
     * @throws NoAuthorException
     * @return ExerciseModel
     */
    public function createFromResource(ExerciseModelResource $modelResource, $authorId = null);

    /**
     * Create and add an exercise model from a resource
     *
     * @param ExerciseModelResource $modelResource
     * @param int                   $authorId
     *
     * @return ExerciseModel
     */
    public function createAndAdd(ExerciseModelResource $modelResource, $authorId);

    /**
     * Add a model from a Resource
     *
     * @param ExerciseModel $model
     *
     * @return ExerciseModel
     */
    public function add(ExerciseModel $model);

    /**
     * Create or update an ExerciseResource object from a ResourceResource
     *
     * @param ExerciseModelResource $modelResource
     * @param ExerciseModel         $model
     *
     * @throws NoAuthorException
     * @return ExerciseModel
     */
    public function updateFromResource(ExerciseModelResource $modelResource, $model);

    /**
     * Save a resource given in form of a ResourceResource
     *
     * @param ExerciseModelResource $modelResource
     * @param int                   $modelId
     *
     * @return ExerciseModel
     */
    public function edit(ExerciseModelResource $modelResource, $modelId);

    /**
     * Save a resource
     *
     * @param ExerciseModel $model
     *
     * @return ExerciseModel
     */
    public function save(ExerciseModel $model);

    /**
     * Delete a resource
     *
     * @param $modelId
     */
    public function remove($modelId);
}
