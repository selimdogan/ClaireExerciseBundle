<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity\SharedMetadataRepository;
use
    SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource\ExerciseResourceServiceInterface;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityService;
use SimpleIT\ClaireExerciseBundle\Service\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class SharedMetadataService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class SharedMetadataService extends TransactionalService
{
    const ENTITY_NAME = 'Entity name';

    /**
     * @var SharedMetadataRepository
     */
    protected $metadataRepository;

    /**
     * @var SharedEntityService
     */
    protected $entityService;

    /**
     * Set metadataRepository
     *
     * @param SharedMetadataRepository $metadataRepository
     */
    public function setMetadataRepository($metadataRepository)
    {
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * Set entity service
     *
     * @param ExerciseResourceServiceInterface $entityService
     */
    public function setEntityService($entityService)
    {
        $this->entityService = $entityService;
    }

    /**
     * Find a metadata by entity id and metakey
     *
     * @param int  $entityId
     * @param int  $metakey
     * @param int  $userId
     * @param bool $noPublic
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @return Metadata
     */
    public function getByEntity($entityId, $metakey, $userId = null, $noPublic = false)
    {
        $entity = $this->entityService->get($entityId);
        if ($userId !== null && $entity->getOwner()->getId() !== $userId
            && $noPublic === true && !$entity->getPublic()
        ) {
            throw new AccessDeniedException();
        }

        return $this->metadataRepository->find(
            array(
                static::ENTITY_NAME => $entityId,
                'key'               => $metakey
            )
        );
    }

    /**
     * Get all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $entityId
     * @param int                   $userId
     *
     * @throws AccessDeniedException
     * @return array
     */
    public function getAll(
        $collectionInformation = null,
        $entityId = null,
        $userId = null
    )
    {
        $entity = null;
        if (!is_null($entityId)) {
            $entity = $this->entityService->get($entityId);
            if (!$entity->getPublic() && $entity->getOwner()->getId() !== $userId) {
                throw new AccessDeniedException();
            }
        }

        return $this->metadataRepository->findAllBy(
            $collectionInformation,
            $entity
        );
    }

    /**
     * Add a metadata to an entity
     *
     * @param int      $entityId
     * @param Metadata $metadata
     * @param int      $userId
     *
     * @throws AccessDeniedException
     * @return Metadata
     */
    public function addToEntity($entityId, $metadata, $userId)
    {
        $entity = $this->entityService->get($entityId);
        if (!$entity->getPublic() && $entity->getOwner()->getId() !== $userId) {
            throw new AccessDeniedException();
        }
        $metadata->setEntity($entity);

        $this->em->persist($metadata);
        $this->em->flush();

        return $metadata;
    }

    /**
     * Save a metadata from an entity
     *
     * @param mixed            $entityId
     * @param MetadataResource $metadata
     * @param string           $metadataKey
     * @param int              $userId
     *
     * @return Metadata
     */
    public function saveFromEntity(
        $entityId,
        MetadataResource $metadata,
        $metadataKey,
        $userId
    )
    {
        $mdToUpdate = $this->getByEntity($entityId, $metadataKey, $userId, true);
        $mdToUpdate->setValue($metadata->getValue());

        $metadata = $this->metadataRepository->update($mdToUpdate);
        $this->em->flush();

        return $metadata;
    }

    /**
     * Remove a metadata from an entity
     *
     * @param mixed $entityId
     * @param mixed $metadataKey
     * @param int   $userId
     */
    public function removeFromEntity($entityId, $metadataKey, $userId)
    {
        $metadata = $this->getByEntity($entityId, $metadataKey, $userId, true);

        $this->metadataRepository->delete($metadata);
        $this->em->flush();
    }

    /**
     * Delete all the metadata for an owner resource
     *
     * @param int $exerciseModelId
     */
    public function deleteAllByEntity($exerciseModelId)
    {
        $this->metadataRepository->deleteAllByEntity($exerciseModelId);
        $this->em->flush();
    }
}
