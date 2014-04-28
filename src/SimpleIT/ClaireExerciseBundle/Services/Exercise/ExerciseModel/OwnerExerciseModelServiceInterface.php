<?php

namespace SimpleIT\ClaireExerciseBundle\Service\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerExerciseModelResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface for class OwnerExerciseModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface OwnerExerciseModelServiceInterface
{
    /**
     * Get an Owner Exercise Model entity
     *
     * @param int $ownerExerciseModelId
     *
     * @return OwnerExerciseModel
     * @throws NonExistingObjectException
     */
    public function get($ownerExerciseModelId);

    /**
     * Get a list of Owner Exercise Models
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $exerciseModelId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $ownerId = null,
        $exerciseModelId = null
    );

    /**
     * Get an OwnerExerciseModel by id and by model
     *
     * @param int $ownerExerciseModelId
     * @param int $exerciseModelId
     *
     * @return OwnerExerciseModel
     */
    public function getByIdAndModel($ownerExerciseModelId, $exerciseModelId);

    /**
     * Create and add an ownerExerciseModel from an ownerExerciseModelResource
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     * @param int                        $modelId
     * @param int                        $ownerId
     *
     * @return OwnerExerciseModel
     */
    public function createAndAdd(
        OwnerExerciseModelResource $ownerExerciseModelResource,
        $modelId,
        $ownerId
    );

    /**
     * Add an ownerExerciseModel
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     *
     * @return OwnerExerciseModel
     */
    public function add(OwnerExerciseModel $ownerExerciseModel);

    /**
     * Save an owner exercise model given in form of an OwnerExerciseModelResource
     *
     * @param OwnerExerciseModelResource $ownerExerciseModelResource
     * @param int                        $ownerExerciseModelId
     * @param int                        $modelId
     *
     * @return OwnerExerciseModel
     */
    public function edit(
        OwnerExerciseModelResource $ownerExerciseModelResource,
        $ownerExerciseModelId,
        $modelId = null
    );

    /**
     * Save an owner exercise model
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     *
     * @return OwnerExerciseModel
     */
    public function save(OwnerExerciseModel $ownerExerciseModel);

    /**
     * Delete an owner exercise model
     *
     * @param $ownerExerciseModelId
     */
    public function remove($ownerExerciseModelId);

    /**
     * Edit all the metadata of an owner exercise model
     *
     * @param int             $ownerExerciseModelId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     */
    public function editMetadata($ownerExerciseModelId, ArrayCollection $metadatas);

    /**
     * Get an OwnerExerciseModel by id and by owner
     *
     * @param int $ownerExerciseModelId
     * @param int $ownerId
     *
     * @return OwnerExerciseModel
     */
    public function getByIdAndOwner($ownerExerciseModelId, $ownerId);
}
