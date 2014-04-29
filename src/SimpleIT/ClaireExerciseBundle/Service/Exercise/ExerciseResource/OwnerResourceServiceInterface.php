<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\OwnerResource;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface for class OwnerResourceService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface OwnerResourceServiceInterface
{
    /**
     * Get an Owner Resource entity
     *
     * @param int              $ownerResourceId
     * @param ExerciseResource $resource
     *
     * @return OwnerResource
     */
    public function get($ownerResourceId, $resource = null);

    /**
     * Get a list of Owner Resources
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $resourceId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $ownerId = null,
        $resourceId = null
    );

    /**
     * Get a list of resources according to constraints and to the owner
     *
     * @param ObjectConstraints $oc
     * @param User              $owner
     *
     * @return array of OwnerResource
     */
    public function getResourcesFromConstraintsByOwner(ObjectConstraints $oc, User $owner);

    /**
     * Get an OwnerResource by resource id and by owner
     *
     * @param int $resourceId
     * @param int $ownerId
     *
     * @return OwnerResource
     */
    public function getByIdAndOwner($resourceId, $ownerId);

    /**
     * Get an OwnerResource by id and by resource
     *
     * @param int $ownerResourceId
     * @param int $resourceId
     *
     * @return OwnerResource
     */
    public function getByIdAndResource($ownerResourceId, $resourceId);

    /**
     * Create and add an ownerResource from an ownerResourceResource
     *
     * @param OwnerResourceResource $ownerResourceResource
     * @param int                   $resourceId
     * @param int                   $ownerId
     *
     * @return OwnerResource
     */
    public function createAndAdd(
        OwnerResourceResource $ownerResourceResource,
        $resourceId,
        $ownerId
    );

    /**
     * Save an owner resource given in form of an OwnerResourceResource
     *
     * @param OwnerResourceResource $ownerResourceResource
     * @param int                   $ownerResourceId
     * @param int                   $resourceId
     *
     * @return OwnerResource
     */
    public function edit(
        OwnerResourceResource $ownerResourceResource,
        $ownerResourceId,
        $resourceId = null
    );

    /**
     * Add an owner resource
     *
     * @param OwnerResource $ownerResource
     *
     * @return OwnerResource
     */
    public function add(OwnerResource $ownerResource);

    /**
     * Save an owner resource
     *
     * @param OwnerResource $ownerResource
     *
     * @return OwnerResource
     */
    public function save(OwnerResource $ownerResource);

    /**
     * Edit all the metadata of a resource
     *
     * @param                 $ownerResourceId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     */
    public function editMetadata($ownerResourceId, ArrayCollection $metadatas);

    /**
     * Delete an owner resource
     *
     * @param $ownerResourceId
     */
    public function remove($ownerResourceId);
}
