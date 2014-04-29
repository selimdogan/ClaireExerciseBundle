<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseObject\ExerciseObject;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
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
     * Find the content of an exerciseResource entity by id
     *
     * @param $resId
     *
     * @return string The XML resource content
     */
    public function getContent($resId);

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
     */
    public function add(ExerciseResource $exerciseResource);

    /**
     * Create an ExerciseResource object from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param int              $authorId
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function createFromResource(
        ResourceResource $resourceResource,
        $authorId = null
    );

    /**
     * Create and add an exerciseResource from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param int              $authorId
     *
     * @return ExerciseResource
     */
    public function createAndAdd(ResourceResource $resourceResource, $authorId);

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
    public function edit(ResourceResource $resourceResource, $resourceId);

    /**
     * Save a resource
     *
     * @param ExerciseResource $exerciseResource
     *
     * @return ExerciseResource
     */
    public function save(ExerciseResource $exerciseResource);

    /**
     * Add a requiredResource to a resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @return ExerciseResource
     */
    public function addRequiredResource($resourceId, $reqResId);

    /**
     * Delete a required resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @return ExerciseResource
     */
    public function deleteRequiredResource($resourceId, $reqResId);

    /**
     * Delete a resource
     *
     * @param $resourceId
     */
    public function remove($resourceId);

    /**
     * Edit the required resources
     *
     * @param int             $resourceId
     * @param ArrayCollection $requiredResources
     *
     * @return ExerciseResource
     */
    public function editRequiredResource($resourceId, ArrayCollection $requiredResources);

    /**
     * Get a resource by id
     *
     * @param $resourceId
     *
     * @return ExerciseResource
     */
    public function get($resourceId);

    /**
     * Get a list of Resources
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $authorId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $authorId = null
    );
}
