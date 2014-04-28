<?php

namespace SimpleIT\ClaireExerciseBundle\Service\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\OwnerResource;
use SimpleIT\ClaireExerciseBundle\Entity\OwnerResourceFactory;
use SimpleIT\ClaireExerciseBundle\Entity\ResourceMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\OwnerResourceRepository;
use SimpleIT\ClaireExerciseBundle\Service\User\UserServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Class OwnerResourceService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceService extends TransactionalService implements OwnerResourceServiceInterface
{
    /**
     * @var OwnerResourceRepository
     */
    private $ownerResourceRepository;

    /**
     * @var MetadataService
     */
    private $metadataService;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var ExerciseResourceServiceInterface
     */
    private $exerciseResourceService;

    /**
     * Set ownerResourceRepository
     *
     * @param OwnerResourceRepository $ownerResourceRepository
     */
    public function setOwnerResourceRepository($ownerResourceRepository)
    {
        $this->ownerResourceRepository = $ownerResourceRepository;
    }

    /**
     * Set metadataService
     *
     * @param MetadataService $metadataService
     */
    public function setMetadataService($metadataService)
    {
        $this->metadataService = $metadataService;
    }

    /**
     * Set userService
     *
     * @param UserServiceInterface $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Set exerciseResourceService
     *
     * @param ExerciseResourceServiceInterface $exerciseResourceService
     */
    public function setExerciseResourceService($exerciseResourceService)
    {
        $this->exerciseResourceService = $exerciseResourceService;
    }

    /**
     * Get an Owner Resource entity
     *
     * @param int              $ownerResourceId
     * @param ExerciseResource $resource
     *
     * @return OwnerResource
     */
    public function get($ownerResourceId, $resource = null)
    {
        if (is_null($resource)) {
            $ownerResource = $this->ownerResourceRepository->find($ownerResourceId);
        } else {
            $ownerResource = $this->ownerResourceRepository->findByIdAndResource
                (
                    $ownerResourceId,
                    $resource
                );
        }

        return $ownerResource;
    }

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
    )
    {
        $resource = null;
        if (!is_null($resourceId)) {
            $resource = $this->exerciseResourceService->get($resourceId);
        }

        $owner = null;
        if (!is_null($ownerId)) {
            $owner = $this->userService->get($ownerId);
        }

        return $this->ownerResourceRepository->findAll($collectionInformation, $owner, $resource);
    }

    /**
     * Get a list of resources according to constraints and to the owner
     *
     * @param ObjectConstraints $oc
     * @param User              $owner
     *
     * @return array of OwnerResource
     */
    public function getResourcesFromConstraintsByOwner(ObjectConstraints $oc, User $owner)
    {
        return $this->ownerResourceRepository->findResourcesFromConstraintsByOwner($oc, $owner);
    }

    /**
     * Get an OwnerResource by id and by owner
     *
     * @param int $resourceId
     * @param int $ownerId
     *
     * @return OwnerResource
     */
    public function getByIdAndOwner($resourceId, $ownerId)
    {
        $owner = $this->userService->get($ownerId);

        return $this->ownerResourceRepository->findByIdAndOwner($resourceId, $owner);
    }

    /**
     * Get an OwnerResource by id and by resource
     *
     * @param int $ownerResourceId
     * @param int $resourceId
     *
     * @return OwnerResource
     */
    public function getByIdAndResource($ownerResourceId, $resourceId)
    {
        $resource = $this->exerciseResourceService->get($resourceId);

        return $this->ownerResourceRepository->findByIdAndResource($ownerResourceId, $resource);
    }

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
    )
    {
        $ownerResource = OwnerResourceFactory::createFromResource($ownerResourceResource);
        $ownerResource->setOwner($this->userService->get($ownerId));
        $ownerResource->setResource($this->exerciseResourceService->get($resourceId));

        $metadata = array();
        foreach ($ownerResourceResource->getMetadata() as $key => $value) {
            $md = ResourceMetadataFactory::create($key, $value);
            $md->setOwnerResource($ownerResource);
            $metadata[] = $md;
        }
        $ownerResource->setMetadata(new ArrayCollection($metadata));

        return $this->add($ownerResource);
    }

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
    )
    {
        $resource = null;
        if (!is_null($resourceId)) {
            $resource = $this->exerciseResourceService->get($resourceId);
        }

        $ownerResource = $this->get($ownerResourceId, $resource);
        $ownerResource->setPublic($ownerResourceResource->getPublic());

        return $this->save($ownerResource);
    }

    /**
     * Add an owner resource
     *
     * @param OwnerResource $ownerResource
     *
     * @return OwnerResource
     * @Transactional
     */
    public function add(OwnerResource $ownerResource)
    {
        $this->ownerResourceRepository->insert($ownerResource);

        return $ownerResource;
    }

    /**
     * Save an owner resource
     *
     * @param OwnerResource $ownerResource
     *
     * @return OwnerResource
     * @Transactional
     */
    public function save(OwnerResource $ownerResource)
    {
        return $this->ownerResourceRepository->update($ownerResource);
    }

    /**
     * Edit all the metadata of a resource
     *
     * @param int             $ownerResourceId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    public function editMetadata($ownerResourceId, ArrayCollection $metadatas)
    {
        $ownerResource = $this->ownerResourceRepository->find($ownerResourceId);

        $this->metadataService->deleteAllByOwnerResource($ownerResourceId);

        $metadataCollection = array();
        foreach ($metadatas as $key => $value) {
            $md = ResourceMetadataFactory::create($key, $value);
            $md->setOwnerResource($ownerResource);
            $metadataCollection[] = $md;
        }
        $ownerResource->setMetadata(new ArrayCollection($metadataCollection));

        return $this->save($ownerResource)->getMetadata();
    }

    /**
     * Delete an owner resource
     *
     * @param $ownerResourceId
     *
     * @Transactional
     */
    public function remove($ownerResourceId)
    {
        $resource = $this->ownerResourceRepository->find($ownerResourceId);
        $this->ownerResourceRepository->delete($resource);
    }
}
