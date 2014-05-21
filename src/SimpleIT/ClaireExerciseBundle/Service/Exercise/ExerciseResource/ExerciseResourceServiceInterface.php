<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Service which manages the exercise resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */

interface ExerciseResourceServiceInterface
{
    /**
     * Get a resource in the form of an ExerciseObject
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

    /**
     * Add a resource from a ResourceResource
     *
     * @param ExerciseResource $exerciseResource
     *
     * @return ExerciseResource
     * @Transactional
     */
    public function add(ExerciseResource $exerciseResource);

    /**
     * Create an ExerciseResource object from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function createFromResource(
        ResourceResource $resourceResource
    );

    /**
     * Create and add an exerciseResource from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     *
     * @return ExerciseResource
     */
    public function createAndAdd(ResourceResource $resourceResource);

    /**
     * Create or update an ExerciseResource object from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param ExerciseResource $exerciseResource
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function updateFromResource(
        ResourceResource $resourceResource,
        $exerciseResource
    );

    /**
     * Save a resource given in form of a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param int              $resourceId
     *
     * @return ExerciseResource
     */
    public function edit(
        ResourceResource $resourceResource,
        $resourceId
    );

    /**
     * Save a resource
     *
     * @param ExerciseResource $exerciseResource
     *
     * @return ExerciseResource
     * @Transactional
     */
    public function save(ExerciseResource $exerciseResource);

    /**
     * Edit all the metadata of a resource
     *
     * @param int             $resourceId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    public function editMetadata($resourceId, ArrayCollection $metadatas);

    /**
     * Add a requiredResource to a resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @return ExerciseResource
     */
    public function addRequiredResource(
        $resourceId,
        $reqResId
    );

    /**
     * Delete a required resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @return ExerciseResource
     */
    public function deleteRequiredResource(
        $resourceId,
        $reqResId
    );

    /**
     * Edit the required resources
     *
     * @param int             $resourceId
     * @param ArrayCollection $requiredResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function editRequiredResource($resourceId, ArrayCollection $requiredResources);

    /**
     * Add a required knowledge to an exercise model
     *
     * @param $resourceId
     * @param $reqKnoId
     *
     * @return ExerciseResource
     */
    public function addRequiredKnowledge(
        $resourceId,
        $reqKnoId
    );

    /**
     * Delete a required knowledge
     *
     * @param $exerciseModelId
     * @param $reqKnoId
     *
     * @return ExerciseResource
     */
    public function deleteRequiredKnowledge(
        $exerciseModelId,
        $reqKnoId
    );

    /**
     * Edit the required knowledges
     *
     * @param int             $resourceId
     * @param ArrayCollection $requiredKnowledges
     *
     * @return ExerciseResource
     */
    public function editRequiredKnowledges(
        $resourceId,
        ArrayCollection $requiredKnowledges
    );

    /**
     * Delete a resource
     *
     * @param $resourceId
     *
     * @Transactional
     */
    public function remove($resourceId);

    /**
     * Get a resource by id
     *
     * @param $resourceId
     *
     * @return ExerciseResource
     */
    public function get(
        $resourceId
    );

    /**
     * Get a list of Resources
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $authorId
     * @param int                   $parentModelId
     * @param int                   $forkFromModelId
     * @param boolean               $isRoot
     * @param boolean               $isPointer
     *
     * @return PaginatorInterface
     */
    public function getAll(
        $collectionInformation = null,
        $ownerId = null,
        $authorId = null,
        $parentModelId = null,
        $forkFromModelId = null,
        $isRoot = null,
        $isPointer = null
    );

    /**
     * Get an ExerciseResource by id and by owner
     *
     * @param int $resourceId
     * @param int $ownerId
     *
     * @return ExerciseResource
     */
    public function getByIdAndOwner($resourceId, $ownerId);
}
