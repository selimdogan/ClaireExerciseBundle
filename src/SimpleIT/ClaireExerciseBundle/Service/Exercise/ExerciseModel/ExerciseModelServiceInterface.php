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
     * Get an entity by its id
     *
     * @param int $entityId
     *
     * @return ExerciseModel
     * @throws NonExistingObjectException
     */
    public function get($entityId);

    /**
     * Create an entity from a resource (no saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     *
     * @param ExerciseModelResource $resource
     *
     * @return ExerciseModel
     */
    public function createFromResource($resource);

    /**
     * Create and add an entity from a resource (saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     *
     * @param ExerciseModelResource $resource
     *
     * @return ExerciseModel
     */
    public function createAndAdd(
        $resource
    );

    /**
     *Add a new entity (saving). The id must be null.
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
     * Update an entity object from a Resource (no saving).
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of an entity can never be modified (ignored if not null)
     *
     * @param ExerciseModelResource $resource
     * @param ExerciseModel   $entity
     *
     * @return ExerciseModel
     */
    public function updateFromResource(
        $resource,
        $entity
    );

    /**
     * Edit and save an entity given in form of a Resource object.
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of the entity that must be modified is stored in the field id.
     * The id of an entity can never be modified.
     *
     * @param ExerciseModelResource $resource The resource corresponding to the entity
     *
     * @return ExerciseModel The edited and saved entity
     */
    public function edit(
        $resource
    );

    /**
     * Save an entity after modifications
     *
     * @param ExerciseModel $entity
     *
     * @return ExerciseModel
     */
    public function save($entity);

    /**
     * Get an entity by its id and by the owner id
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
     * @return CommonModel
     */
    public function getModel($exerciseModelId);

    /**
     * Get an exercise model from an entity (business object, no entity)
     *
     * @param ExerciseModel $entity
     *
     * @return CommonModel
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
     * Edit all the required resources
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
     * Edit all the required knowledges
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
