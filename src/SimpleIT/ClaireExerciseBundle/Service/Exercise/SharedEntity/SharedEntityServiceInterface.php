<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntityMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface SharedEntityServiceInterface
{
    /**
     * Get an entity
     *
     * @param int $entityId
     *
     * @return SharedEntity
     * @throws NonExistingObjectException
     */
    public function get($entityId);

    /**
     * Get a list of entities
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
     * Create an entity from a resource
     *
     * @param SharedResource $resource
     *
     * @return SharedEntity
     */
    public function createFromResource($resource);

    /**
     * Create and add an entity from a resource
     *
     * @param SharedResource $resource
     *
     * @return SharedEntity
     */
    public function createAndAdd(
        $resource
    );

    /**
     * Add an entity
     *
     * @param SharedEntity $entity
     *
     * @return SharedEntity
     * @Transactional
     */
    public function add(
        $entity
    );

    /**
     * Update an entity object from a Resource
     *
     * @param SharedResource $resource
     * @param SharedEntity   $entity
     *
     * @return SharedEntity
     */
    public function updateFromResource(
        $resource,
        $entity
    );

    /**
     * Save an entity given in form of a Resource
     *
     * @param SharedResource $resource
     * @param int            $entityId
     *
     * @return SharedEntity
     */
    public function edit(
        $resource,
        $entityId
    );

    /**
     * Save an entity
     *
     * @param SharedEntity $entity
     *
     * @return SharedEntity
     * @Transactional
     */
    public function save($entity);

    /**
     * Delete an entity
     *
     * @param $entityId
     *
     * @Transactional
     */
    public function remove($entityId);

    /**
     * Edit all the metadata of an entity
     *
     * @param int             $entityId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    public function editMetadata($entityId, ArrayCollection $metadatas);

    /**
     * Get an entity by id and by owner
     *
     * @param int $entityId
     * @param int $ownerId
     *
     * @return SharedEntity
     */
    public function getByIdAndOwner($entityId, $ownerId);
}
