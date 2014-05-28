<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityServiceInterface;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * Service which manages the exercise resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */

interface ExerciseResourceServiceInterface extends SharedEntityServiceInterface
{
    /**
     * Get an entity
     *
     * @param int $entityId
     *
     * @return ExerciseResource
     * @throws NonExistingObjectException
     */
    public function get($entityId);

    /**
     * Create an entity from a resource (no saving).
     * Required fields: type, title, [content or parent], owner, author, archived, metadata
     * Must be null: id
     *
     * @param ResourceResource $resource
     *
     * @return ExerciseResource
     */
    public function createFromResource($resource);

    /**
     * Create and add an entity from a resource (saving).
     * Required fields: type, title, [content or parent], owner, author, archived, metadata
     * Must be null: id
     *
     * @param ResourceResource $resource
     *
     * @return ExerciseResource
     */
    public function createAndAdd(
        $resource
    );

    /**
     * Add a new entity (saving). The id must be null.
     *
     * @param ExerciseResource $entity
     *
     * @return ExerciseResource
     */
    public function add(
        $entity
    );

    /**
     * Update an entity object from a Resource (no saving).
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of an entity can never be modified (ignored if not null)
     *
     * @param ResourceResource $resourceResource
     * @param ExerciseResource $exerciseResource
     *
     * @return ExerciseResource
     */
    public function updateFromResource(
        $resourceResource,
        $exerciseResource
    );

    /**
     * Edit and save an entity given in form of a Resource object.
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of the entity that must be modified is stored in the field id.
     * The id of an entity can never be modified.
     *
     * @param ResourceResource $resource The resource corresponding to the entity
     *
     * @return ExerciseResource The edited and saved entity
     */
    public function edit(
        $resource
    );

    /**
     * Save an entity after modifications
     *
     * @param ExerciseResource $entity
     *
     * @return ExerciseResource
     */
    public function save($entity);

    /**
     * Get an entity by its id and by the owner id
     *
     * @param int $entityId
     * @param int $ownerId
     *
     * @return ExerciseResource
     */
    public function getByIdAndOwner($entityId, $ownerId);

    /**
     * Add a requiredResource to a resource entity (saving)
     *
     * @param int $resourceId The id of the requiring resource entity
     * @param int $reqResId   The id of the required resource entity
     *
     * @return ExerciseResource
     */
    public function addRequiredResource(
        $resourceId,
        $reqResId
    );

    /**
     * Delete a required resource (saving)
     *
     * @param int $resourceId The id of the requiring resource entity
     * @param int $reqResId   The id of the required resource entity
     *
     * @return ExerciseResource
     */
    public function deleteRequiredResource(
        $resourceId,
        $reqResId
    );

    /**
     * Edit the required resources (saving)
     *
     * @param int             $resourceId        The id of the requiring resource entity
     * @param ArrayCollection $requiredResources A collection of int: id of the required entities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function editRequiredResource($resourceId, ArrayCollection $requiredResources);

    /**
     * Add a required knowledge to a resource entity (saving)
     *
     * @param int $resourceId The id of the requiring resource entity
     * @param int $reqKnoId   The id of the required knowledge entity
     *
     * @return ExerciseResource
     */
    public function addRequiredKnowledge(
        $resourceId,
        $reqKnoId
    );

    /**
     * Delete a required knowledge (saving)
     *
     * @param int $resourceId The id of the requiring resource entity
     * @param int $reqKnoId   The id of the required knowledge entity
     *
     * @return ExerciseResource
     */
    public function deleteRequiredKnowledge(
        $resourceId,
        $reqKnoId
    );

    /**
     * Edit the required knowledges (saving)
     *
     * @param int             $resourceId         The id of the requiring resource entity
     * @param ArrayCollection $requiredKnowledges A collection of int: id of the required knowledges
     *
     * @return ExerciseResource
     */
    public function editRequiredKnowledges(
        $resourceId,
        ArrayCollection $requiredKnowledges
    );

    /**
     * Get a resource in the form of an ExerciseObject (useful for exercise generation)
     *
     * @param ObjectId $resId
     * @param User     $owner
     *
     * @throws ApiNotFoundException
     * @return ExerciseObject
     */
    public function getExerciseObject(ObjectId $resId, User $owner);

    /**
     * Returns a list of ExerciseObjects matching the constraints
     *
     * @param ObjectConstraints $oc The constraints
     * @param int               $numberOfObjects
     * @param User              $owner
     *
     * @return array An array of ExerciseObjects
     */
    public function getExerciseObjectsFromConstraints(
        ObjectConstraints $oc,
        $numberOfObjects,
        User $owner
    );
}
