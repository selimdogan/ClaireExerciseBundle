<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityServiceInterface;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * Interface for service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ExerciseModelServiceInterface extends SharedEntityServiceInterface
{
    /**
     * Get an entity
     *
     * @param int $entityId
     *
     * @return ExerciseModel
     * @throws NonExistingObjectException
     */
    public function get($entityId);

    /**
     * Create an entity from a resource
     *
     * @param ExerciseModelResource $resource
     *
     * @return ExerciseModel
     */
    public function createFromResource($resource);

    /**
     * Create and add an entity from a resource
     *
     * @param ExerciseModelResource $resource
     *
     * @return ExerciseModel
     */
    public function createAndAdd(
        $resource
    );

    /**
     * Add an entity
     *
     * @param ExerciseModel $entity
     *
     * @return ExerciseModel
     * @Transactional
     */
    public function add(
        $entity
    );

    /**
     * Update an entity object from a Resource
     *
     * @param ExerciseModelResource $resource
     * @param ExerciseModel         $entity
     *
     * @return ExerciseModel
     */
    public function updateFromResource(
        $resource,
        $entity
    );

    /**
     * Save an entity given in form of a Resource
     *
     * @param ExerciseModelResource $resource
     * @param int                   $entityId
     *
     * @return ExerciseModel
     */
    public function edit(
        $resource,
        $entityId
    );

    /**
     * Save an entity
     *
     * @param ExerciseModel $entity
     *
     * @return ExerciseModel
     * @Transactional
     */
    public function save($entity);

    /**
     * Get an entity by id and by owner
     *
     * @param int $entityId
     * @param int $ownerId
     *
     * @return ExerciseModel
     */
    public function getByIdAndOwner($entityId, $ownerId);

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
     * Add a requiredResource to an exercise model
     *
     * @param $exerciseModelId
     * @param $reqResId
     *
     * @return ExerciseModel
     */
    public function addRequiredResource(
        $exerciseModelId,
        $reqResId
    );

    /**
     * Delete a required resource
     *
     * @param $exerciseModelId
     * @param $reqResId
     *
     * @return ExerciseModel
     */
    public function deleteRequiredResource(
        $exerciseModelId,
        $reqResId
    );

    /**
     * Edit the required resources
     *
     * @param int             $exerciseModelId
     * @param ArrayCollection $requiredResources
     *
     * @return ExerciseModel
     */
    public function editRequiredResource(
        $exerciseModelId,
        ArrayCollection $requiredResources
    );

    /**
     * Add a required knowledge to an exercise model
     *
     * @param $exerciseModelId
     * @param $reqKnoId
     *
     * @return ExerciseModel
     */
    public function addRequiredKnowledge(
        $exerciseModelId,
        $reqKnoId
    );

    /**
     * Delete a required knowledge
     *
     * @param $exerciseModelId
     * @param $reqKnoId
     *
     * @return ExerciseModel
     */
    public function deleteRequiredKnowledge(
        $exerciseModelId,
        $reqKnoId
    );

    /**
     * Edit the required knowledges
     *
     * @param int             $exerciseModelId
     * @param ArrayCollection $requiredKnowledges
     *
     * @return ExerciseModel
     */
    public function editRequiredKnowledges(
        $exerciseModelId,
        ArrayCollection $requiredKnowledges
    );
}
