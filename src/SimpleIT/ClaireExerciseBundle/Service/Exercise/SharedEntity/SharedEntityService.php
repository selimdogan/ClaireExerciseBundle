<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntityMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException;
use SimpleIT\ClaireExerciseBundle\Exception\MissingIdException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResourceFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;
use SimpleIT\ClaireExerciseBundle\Service\Serializer\SerializerInterface;
use SimpleIT\ClaireExerciseBundle\Service\User\UserService;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedEntityService extends TransactionalService implements SharedEntityServiceInterface
{
    const ENTITY_TYPE = '';

    /**
     * @var SharedEntityRepository $entityRepository
     */
    protected $entityRepository;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var SharedMetadataService
     */
    private $metadataService;

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Set SharedEntityRepository
     *
     * @param SharedEntityRepository $entityRepository
     */
    public function setEntityRepository($entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * Set userService
     *
     * @param UserService $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Set metadataService
     *
     * @param SharedMetadataService $metadataService
     */
    public function setMetadataService($metadataService)
    {
        $this->metadataService = $metadataService;
    }

    /**
     * @inheritdoc
     */
    public function get($entityId)
    {
        $entity = $this->entityRepository->find($entityId);
        if (is_null($entity)) {
            throw new NonExistingObjectException();
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function getParent($entityId)
    {
        $entity = $this->get($entityId);

        while ($entity->getContent() === null) {
            if ($entity->getParent() === null) {
                throw new InconsistentEntityException('The entity has no content and no parent');
            }
            $entity = $entity->getParent();
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function getAll(
        $collectionInformation = null,
        $ownerId = null,
        $authorId = null,
        $parentEntityId = null,
        $forkFromEntityId = null,
        $isRoot = null,
        $isPointer = null
    )
    {
        $owner = null;
        if (!is_null($ownerId)) {
            $owner = $this->userService->get($ownerId);
        }

        $author = null;
        if (!is_null($authorId)) {
            $author = $this->userService->get($authorId);
        }

        $parentEntity = null;
        if (!is_null($parentEntityId)) {
            $parentEntity = $this->get($parentEntityId);
        }

        $forkFromEntity = null;
        if (!is_null($forkFromEntityId)) {
            $forkFromEntity = $this->get($forkFromEntityId);
        }

        return $this->entityRepository->findAll(
            $collectionInformation,
            $owner,
            $author,
            $parentEntity,
            $forkFromEntity,
            $isRoot,
            $isPointer
        );
    }

    /**
     * Fill an entity from a resource
     *
     * @param SharedEntity   $entity
     * @param SharedResource $sharedResource
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException
     * @return SharedEntity
     */
    protected function fillFromResource(
        SharedEntity &$entity,
        SharedResource $sharedResource
    )
    {
        // complete
        $entity->setComplete(
            $this->checkEntityComplete(
                $sharedResource->getType(),
                $sharedResource->getParent(),
                $sharedResource->getContent()
            )
        );

        // author
        if (is_null($sharedResource->getAuthor())) {
            throw new NoAuthorException();
        } else {
            $authorId = $sharedResource->getAuthor();
        }
        $entity->setAuthor(
            $this->userService->get($authorId)
        );

        // owner
        if (is_null($sharedResource->getOwner())) {
            throw new NoAuthorException('No owner for this entity...');
        } else {
            $ownerId = $sharedResource->getOwner();
        }
        $entity->setOwner(
            $this->userService->get($ownerId)
        );

        // parent entity
        if (!is_null($sharedResource->getParent())) {
            $entity->setParent(
                $this->get($sharedResource->getParent())
            );
        }

        // fork from
        if (!is_null($sharedResource->getForkFrom())) {
            $entity->setForkFrom(
                $this->get($sharedResource->getForkFrom())
            );
        }

        // metadata
        $metadata = array();
        $resMetadata = $sharedResource->getMetadata();
        if (!empty($resMetadata)) {
            foreach ($resMetadata as $key => $value) {
                $md = SharedEntityMetadataFactory::create(static::ENTITY_TYPE, $key, $value);
                $md->setEntity($entity);
                $metadata[] = $md;
            }
        }
        $entity->setMetadata(new ArrayCollection($metadata));

        return $entity;
    }

    /**
     * @inheritdoc
     */
    abstract public function createFromResource($resource);

    /**
     * @inheritdoc
     */
    public function createAndAdd(
        $resource
    )
    {
        $entity = $this->createFromResource($resource);

        return $this->add($entity);
    }

    /**
     * @inheritdoc
     */
    public function add(
        $entity
    )
    {
        $entity = $this->entityRepository->insert($entity);
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Update an entity object from a Resource
     *
     * @param SharedResource $resource
     * @param SharedEntity   $entity
     * @param string         $storageGroup
     */
    protected function updateFromSharedResource(
        SharedResource $resource,
        SharedEntity &$entity,
        $storageGroup
    )
    {
        if (!is_null($resource->getTitle())) {
            $entity->setTitle($resource->getTitle());
        }

        if (!is_null($resource->getType())) {
            $entity->setType($resource->getType());
        }

        if (!is_null($resource->getPublic())) {
            $entity->setPublic($resource->getPublic());
        }

        if (!is_null($resource->getDraft())) {
            $entity->setDraft($resource->getDraft());
        }

        if (!is_null($resource->getComplete())) {
            $entity->setComplete($resource->getComplete());
        }

        $content = $resource->getContent();
        if (!is_null($content)) {
            $context = SerializationContext::create();
            $context->setGroups(array($storageGroup, 'Default'));
            $entity->setContent(
                $this->serializer->jmsSerialize($content, 'json', $context)
            );

            $this->validateType($content, $entity->getType());

            if ($entity->getParent() === null) {
                $parentId = null;
            } else {
                $parentId = $entity->getParent()->getId();
            }
            // Check if the entity is complete with the new content
            $oldComplete = $entity->getComplete();
            $newComplete = $this->checkEntityComplete(
                $entity->getType(),
                $parentId,
                $content
            );
            $entity->setComplete($newComplete);

            // update children if necessary
            if ($oldComplete != $newComplete) {
                $this->updateChildrenComplete($entity, $newComplete);
            }
        }
    }

    /**
     * Update the complete property of all the children of an entity
     *
     * @param SharedEntity $entity
     * @param bool         $complete
     */
    private function updateChildrenComplete($entity, $complete)
    {
        /** @var SharedEntity $child */
        foreach ($entity->getChildren() as $child) {
            $child->setComplete($complete);

            if (count($child->getChildren()) > 0) {
                $this->updateChildrenComplete($child, $complete);
            }
        }
    }

    /**
     * @inheritdoc
     */
    abstract public function updateFromResource(
        $resource,
        $entity
    );

    /**
     * @inheritdoc
     */
    public function edit(
        $resource
    )
    {
        if (is_null($resource->getId())) {
            throw new MissingIdException();
        }

        $entity = $this->get($resource->getId());
        $entity = $this->updateFromResource(
            $resource,
            $entity
        );

        return $this->save($entity);
    }

    /**
     * @inheritdoc
     */
    public function save($entity)
    {
        $entity = $this->entityRepository->update($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function remove($entityId)
    {
        $entity = $this->entityRepository->find($entityId);
        try {
            $this->entityRepository->delete($entity);
            $this->em->flush();
        } catch (DBALException $dbale) {
            throw new EntityDeletionException('This entity is needed and cannot be deleted');
        }
    }

    /**
     * @inheritdoc
     */
    public function editMetadata($entityId, ArrayCollection $metadatas)
    {
        /** @var SharedEntity $entity */
        $entity = $this->entityRepository->find($entityId);

        $this->metadataService->deleteAllByEntity($entityId);

        $metadataCollection = array();
        foreach ($metadatas as $key => $value) {
            $md = SharedEntityMetadataFactory::create(static::ENTITY_TYPE, $key, $value);
            $md->setEntity($entity);
            $metadataCollection[] = $md;
        }
        $entity->setMetadata(new ArrayCollection($metadataCollection));

        $metadatas = $this->save($entity)->getMetadata();

        $this->em->flush();

        return $metadatas;
    }

    /**
     * @inheritdoc
     */
    public function getByIdAndOwner($entityId, $ownerId)
    {
        $owner = $this->userService->get($ownerId);

        return $this->entityRepository->findByIdAndOwner($entityId, $owner);
    }

    /**
     * Get a resource view of the entity with a content. If the entity has a content,
     * it just gets the resource view of the entity. If the entity has no content (and thus a
     * parent), the content of the resource is filled with the parent content
     *
     * @param int $entityId
     *
     * @return SharedResource
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException
     */
    public function getContentFullResource($entityId)
    {
        $entity = $this->get($entityId);
        $resource = SharedResourceFactory::createFromEntity($entity, static::ENTITY_TYPE);

        return $this->getContentFullResourceFromResource($resource);
    }

    /**
     * Get a list of resource with content.
     * For each entity, if it has a content, it just gets the resource view of the entity. If it
     * has no content (and thus a parent), the content of the resource is filled with the parent
     * content
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerId
     * @param int                   $authorId
     * @param int                   $parentEntityId
     * @param int                   $forkFromEntityId
     * @param int                   $isRoot
     * @param int                   $isPointer
     *
     * @return array An array of SharedResource
     */
    public function getAllContentFullResources(
        $collectionInformation = null,
        $ownerId = null,
        $authorId = null,
        $parentEntityId = null,
        $forkFromEntityId = null,
        $isRoot = null,
        $isPointer = null
    )
    {
        $entities = $this->getAll(
            $collectionInformation,
            $ownerId,
            $authorId,
            $parentEntityId,
            $forkFromEntityId,
            $isRoot,
            $isPointer
        );

        return $this->getAllContentFullResourcesFromEntityList($entities);
    }

    /**
     * Get a list of resource with content from a list of entities in a PaginatorInterface.
     * For each entity, if it has a content, it just gets the resource view of the entity. If it
     * has no content (and thus a parent), the content of the resource is filled with the parent
     * content
     *
     * @param PaginatorInterface $entities
     *
     * @return array
     */
    public function getAllContentFullResourcesFromEntityList(PaginatorInterface $entities)
    {
        $resources = SharedResourceFactory::createFromEntityCollection(
            $entities,
            static::ENTITY_TYPE
        );

        // find a content for every pointer resource
        /** @var SharedResource $resource */
        foreach ($resources as &$resource) {
            if ($resource->getContent() === null) {
                $resource = $this->getContentFullResourceFromResource($resource);
            }
        }

        return $resources;
    }

    /**
     * Get a resource with a content. If the resource has a content,
     * it just gets the resource view of the entity. If the resource has no content (and thus a
     * parent), the content of the resource is filled with the parent content
     *
     * @param SharedResource $resource
     *
     * @return SharedResource
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException
     */
    private function getContentFullResourceFromResource(SharedResource $resource)
    {
        $entity = $this->get($resource->getId());

        while ($entity->getContent() === null) {
            if ($entity->getParent() === null) {
                throw new InconsistentEntityException('Entity must have a content or a parent');
            }
            $entity = $entity->getParent();
        }

        $parentResource = SharedResourceFactory::createFromEntity($entity, static::ENTITY_TYPE);
        $resource->setContent($parentResource->getContent());
        $resource->setMetadata($parentResource->getMetadata());

        return $resource;
    }

    /**
     * Check if the content of an entity is sufficient to use it to generate exercises.
     *
     * @param string                                     $type
     * @param int                                        $parentEntityId
     * @param CommonModel|CommonResource|CommonKnowledge $content
     *
     * @throws \LogicException
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InvalidModelException
     * @return boolean True if the model is complete
     */
    abstract protected function checkEntityComplete(
        $type,
        $parentEntityId,
        $content
    );

    /**
     * Throws an exception if the content does not match the type
     *
     * @param $content
     * @param $type
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InvalidTypeException
     */
    abstract protected function validateType(
        $content,
        $type
    );

    /**
     * @inheritdoc
     */
    public function subscribe($ownerId, $parentEntityId)
    {
        $owner = $this->userService->get($ownerId);
        $parent = $this->get($parentEntityId);

        $entity = clone($parent);
        $entity->setId(null);
        $entity->setContent(null);
        $entity->setArchived(false);
        $entity->setOwner($owner);
        $entity->setMetadata(new ArrayCollection());
        $entity->setForkFrom(null);
        $entity->setParent($parent);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Duplicate an entity (same owner).
     *
     * @param int $originalId The id of the entity to be duplicated
     *
     * @return SharedEntity
     */
    public function duplicate($originalId)
    {
        $original = $this->get($originalId);

        $entity = clone($original);
        $entity->setId(null);
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Import an entity. Base work.
     *
     * @param int $ownerId
     * @param int $originalId The id of the original entity that must be duplicated
     *
     * @return SharedEntity
     */
    protected function parentImport($ownerId, $originalId)
    {
        $original = $this->get($originalId);

        // clone original
        $entity = clone($original);
        $entity->setId(null);
        $entity->setOwner($this->userService->get($ownerId));
        $entity->setForkFrom($original);

        return $entity;
    }

    /**
     * Import an entity. The entity is duplicated and the required entities are also imported.
     *
     * @param int  $ownerId
     * @param int  $originalId The id of the original entity that must be duplicated
     *
     * @return SharedEntity
     */
    abstract public function import($ownerId, $originalId);

    /**
     * Import an entity if no direct children is owned by the user. (no flush if existing)
     *
     * @param int $ownerId
     * @param int $originalId The id of the original entity that must be duplicated
     *
     * @return int The id of the imported or already existing entity
     */
    public function importOrLink($ownerId, $originalId)
    {
        try {
            $fork = $this->entityRepository->findByForkFromAndOwner($originalId, $ownerId);

            return $fork->getId();
        } catch (NonExistingObjectException $neoe) {
            $entity = $this->import($ownerId, $originalId, false);

            return $entity->getId();
        }
    }
}
