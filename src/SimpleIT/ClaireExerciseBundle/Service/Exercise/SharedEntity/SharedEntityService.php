<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntityMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\SharedResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedEntityRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource\SharedMetadataService;
use SimpleIT\ClaireExerciseBundle\Service\Serializer\SerializerInterface;
use SimpleIT\ClaireExerciseBundle\Service\User\UserService;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedEntityService extends TransactionalService
{
    const EXERCISE_MODEL = 'exerciseModel';

    const RESOURCE = 'resource';

    const KNOWLEDGE = 'knowledge';

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
     * @param \SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel\MetadataService $metadataService
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

        $parentModel = null;
        if (!is_null($parentModelId)) {
            $parentModel = $this->get($parentModelId);
        }

        $forkFromModel = null;
        if (!is_null($forkFromModelId)) {
            $forkFromModel = $this->get($forkFromModelId);
        }

        return $this->entityRepository->findAll(
            $collectionInformation,
            $owner,
            $author,
            $parentModel,
            $forkFromModel,
            $isRoot,
            $isPointer
        );
    }

    /**
     * Fill an entity from a resource
     *
     * @param string         $entityType
     * @param SharedEntity   $entity
     * @param SharedResource $sharedResource
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException
     * @return SharedEntity
     */
    public function fillFromResource(
        $entityType,
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
            throw new NoAuthorException('No owner for this model...');
        } else {
            $ownerId = $sharedResource->getOwner();
        }
        $entity->setOwner(
            $this->userService->get($ownerId)
        );

        // parent model
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
                $md = SharedEntityMetadataFactory::create($entityType, $key, $value);
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
        SharedResource $resource
    )
    {
        $model = $this->createFromResource($resource);

        return $this->add($model);
    }

    /**
     * Add an entity
     *
     * @param SharedEntity $entity
     *
     * @return SharedEntity
     * @Transactional
     */
    public function add(
        SharedEntity $entity
    )
    {
        $this->entityRepository->insert($entity);

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
        SharedResource $resource,
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
     * @Transactional
     */
    public function save($entity)
    {
        return $this->entityRepository->update($entity);
    }

    /**
     * Delete an entity
     *
     * @param $entityId
     *
     * @Transactional
     */
    public function remove($entityId)
    {
        $entity = $this->entityRepository->find($entityId);
        $this->entityRepository->delete($entity);
    }

    /**
     * Edit all the metadata of an entity
     *
     * @param int             $entityId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    abstract public function editMetadata($entityId, ArrayCollection $metadatas);

    /**
     * Edit all the metadata of an entity
     *
     * @param                 $entityType
     * @param int             $entityId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    protected function editMetadataByEntityType($entityType, $entityId, ArrayCollection $metadatas)
    {
        /** @var SharedEntity $entity */
        $entity = $this->entityRepository->find($entityId);

        $this->metadataService->deleteAllByEntity($entityId);

        $metadataCollection = array();
        foreach ($metadatas as $key => $value) {
            $md = SharedEntityMetadataFactory::create($entityType, $key, $value);
            $md->setEntity($entity);
            $metadataCollection[] = $md;
        }
        $entity->setMetadata(new ArrayCollection($metadataCollection));

        return $this->save($entity)->getMetadata();
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
