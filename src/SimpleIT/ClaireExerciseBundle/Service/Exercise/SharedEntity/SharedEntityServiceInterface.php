<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntityMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Interface for service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface SharedEntityServiceInterface
{
    /**
     * Get an entity by its id
     *
     * @param int $entityId
     *
     * @return SharedEntity
     * @throws NonExistingObjectException
     */
    public function get($entityId);

    /**
     * Get the final parent entity (the one with content) pointed by an entity
     *
     * @param int $entityId
     *
     * @return SharedEntity
     * @throws InconsistentEntityException
     */
    public function getParent($entityId);

    /**
     * Get a list of entities according to restrictions. Parameters left to null are not taken in
     * account.
     *
     * @param CollectionInformation $collectionInformation The collection information that can contain filters, sorting or pagination.
     * @param int                   $ownerId               Entities owned by this owner
     * @param int                   $authorId              Entities that have this author
     * @param int                   $parentEntityId        Entities that have this one as parent
     * @param int                   $forkFromEntityId      Entities that are forked from
     * @param boolean               $isRoot                Entities that are root (not forked from) or not
     * @param boolean               $isPointer             Entities that are pointer (no content) or not (no parent)
     *
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $ownerId = null,
        $authorId = null,
        $parentEntityId = null,
        $forkFromEntityId = null,
        $isRoot = null,
        $isPointer = null
    );

    /**
     * Create an entity from a resource (no saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     * Not used (computed) : required resources or knowledge
     *
     * @param SharedResource $resource
     *
     * @return SharedEntity
     */
    public function createFromResource($resource);

    /**
     * Create and add an entity from a resource (saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     *
     * @param SharedResource $resource
     *
     * @return SharedEntity
     */
    public function createAndAdd($resource);

    /**
     * Add a new entity (saving). The id must be null.
     *
     * @param SharedEntity $entity
     *
     * @return SharedEntity
     */
    public function add(
        $entity
    );

    /**
     * Update an entity object from a Resource (no saving).
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of an entity can never be modified (ignored if not null)
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
     * Edit and save an entity given in form of a Resource object.
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of the entity that must be modified is stored in the field id.
     * The id of an entity can never be modified.
     *
     * @param SharedResource $resource The resource corresponding to the entity
     *
     * @return SharedEntity The edited and saved entity
     */
    public function edit(
        $resource
    );

    /**
     * Save an entity after modifications
     *
     * @param SharedEntity $entity An entity
     *
     * @return SharedEntity The saved entity
     */
    public function save($entity);

    /**
     * Delete an entity
     *
     * @param int $entityId The id of the entity
     */
    public function remove($entityId);

    /**
     * Edit all the metadata of an entity. Old ones are removed and replaced by new ones.
     * Keywords must be set in the appropriate field of metadata.
     *
     * @param int             $entityId
     * @param ArrayCollection $metadatas An ArrayCollection of the form (string)metaKey => (string)metaValue
     *
     * @return Collection The collection of metadata entities
     */
    public function editMetadata($entityId, ArrayCollection $metadatas);

    /**
     * Get an entity by its id and by the owner id
     *
     * @param int $entityId
     * @param int $ownerId
     *
     * @return SharedEntity
     */
    public function getByIdAndOwner($entityId, $ownerId);

    /**
     * Subscribe to an entity: the new entity is a pointer to the parent entity. It has no
     * content and no metadata because these elements rely on the parent.
     *
     * @param int $ownerId        The id of the owner who wants to get the new pointer
     * @param int $parentEntityId The id of the parent entity
     *
     * @return SharedEntity
     */
    public function subscribe($ownerId, $parentEntityId);

    /**
     * Import an entity. The entity is duplicated and the required entities are also imported.
     *
     * @param int $ownerId
     * @param int $originalId The id of the original entity that must be duplicated
     *
     * @return SharedEntity
     */
    public function import($ownerId, $originalId);

    /**
     * Import an entity from the entity. Base work.
     *
     * @param int          $ownerId
     * @param SharedEntity $original The original entity that must be duplicated
     *
     * @return SharedEntity
     */
    public function importByEntity($ownerId, $original);

    /**
     * Import an entity if no direct children is owned by the user. (no flush if existing)
     *
     * @param int $ownerId
     * @param int $originalId The id of the original entity that must be duplicated
     *
     * @return int The id of the imported or already existing entity
     */
    public function importOrLink($ownerId, $originalId);
}
