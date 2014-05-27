<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\DBALException;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntityMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResource;
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
     * Get an entity
     *
     * @param int $entityId
     *
     * @return SharedEntity
     * @throws NonExistingObjectException
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
     * Get a list of entities
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $authorId
     * @param int                   $parentEntityId
     * @param int                   $forkFromEntityId
     * @param boolean               $isRoot
     * @param boolean               $isPointer
     *
     * @return PaginatorInterface
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
     * Create an entity from a resource
     *
     * @param SharedResource $resource
     *
     * @return mixed
     */
    abstract public function createFromResource($resource);

    /**
     * Create and add an entity from a resource
     *
     * @param SharedResource $resource
     *
     * @return SharedEntity
     */
    public function createAndAdd(
        $resource
    )
    {
        $entity = $this->createFromResource($resource);

        return $this->add($entity);
    }

    /**
     * Add an entity
     *
     * @param SharedEntity $entity
     *
     * @return SharedEntity
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

        $content = $resource->getContent();
        if (!is_null($content)) {
            $context = SerializationContext::create();
            $context->setGroups(array($storageGroup, 'Default'));
            $entity->setContent(
                $this->serializer->jmsSerialize($content, 'json', $context)
            );
        }
    }

    /**
     * Update an entity object from a Resource
     *
     * @param SharedResource $resource
     * @param SharedEntity   $entity
     *
     * @return SharedEntity
     */
    abstract public function updateFromResource(
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
    )
    {
        $entity = $this->get($entityId);
        $entity = $this->updateFromResource(
            $resource,
            $entity
        );

        return $this->save($entity);
    }

    /**
     * Save an entity
     *
     * @param SharedEntity $entity
     *
     * @return SharedEntity
     */
    public function save($entity)
    {
        $entity = $this->entityRepository->update($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Delete an entity
     *
     * @param $entityId
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException
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
     * Edit all the metadata of an entity
     *
     * @param int             $entityId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
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
     * Get an entity by id and by owner
     *
     * @param int $entityId
     * @param int $ownerId
     *
     * @return SharedEntity
     */
    public function getByIdAndOwner($entityId, $ownerId)
    {
        $owner = $this->userService->get($ownerId);

        return $this->entityRepository->findByIdAndOwner($entityId, $owner);
    }
}
