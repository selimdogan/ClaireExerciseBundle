<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerKnowledgeResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\OwnerKnowledge;
use SimpleIT\ClaireExerciseBundle\Entity\KnowledgeMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Entity\OwnerKnowledgeFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge\OwnerKnowledgeRepository;
use SimpleIT\ClaireExerciseBundle\Service\User\UserServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Class OwnerKnowledgeService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeService extends TransactionalService implements OwnerKnowledgeServiceInterface
{
    /**
     * @var OwnerKnowledgeRepository
     */
    private $ownerKnowledgeRepository;

    /**
     * @var MetadataService
     */
    private $metadataService;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var KnowledgeServiceInterface
     */
    private $knowledgeService;

    /**
     * Set ownerKnowledgeRepository
     *
     * @param OwnerKnowledgeRepository $ownerKnowledgeRepository
     */
    public function setOwnerKnowledgeRepository($ownerKnowledgeRepository)
    {
        $this->ownerKnowledgeRepository = $ownerKnowledgeRepository;
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
     * Set knowledgeService
     *
     * @param KnowledgeServiceInterface $knowledgeService
     */
    public function setKnowledgeService($knowledgeService)
    {
        $this->knowledgeService = $knowledgeService;
    }

    /**
     * Get an Owner Knowledge entity
     *
     * @param int       $ownerKnowledgeId
     * @param Knowledge $knowledge
     *
     * @return OwnerKnowledge
     */
    public function get($ownerKnowledgeId, $knowledge = null)
    {
        if (is_null($knowledge)) {
            $ownerKnowledge = $this->ownerKnowledgeRepository->find($ownerKnowledgeId);
        } else {
            $ownerKnowledge = $this->ownerKnowledgeRepository->findByIdAndKnowledge
                (
                    $ownerKnowledgeId,
                    $knowledge
                );
        }

        return $ownerKnowledge;
    }

    /**
     * Get a list of Owner Knowledges
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $knowledgeId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $ownerId = null,
        $knowledgeId = null
    )
    {
        $knowledge = null;
        if (!is_null($knowledgeId)) {
            $knowledge = $this->knowledgeService->get($knowledgeId);
        }

        $owner = null;
        if (!is_null($ownerId)) {
            $owner = $this->userService->get($ownerId);
        }

        return $this->ownerKnowledgeRepository->findAll($collectionInformation, $owner, $knowledge);
    }

    /**
     * Get an OwnerKnowledge by id and by owner
     *
     * @param int $knowledgeId
     * @param int $ownerId
     *
     * @return OwnerKnowledge
     */
    public function getByIdAndOwner($knowledgeId, $ownerId)
    {
        $owner = $this->userService->get($ownerId);

        return $this->ownerKnowledgeRepository->findByIdAndOwner($knowledgeId, $owner);
    }

    /**
     * Get an OwnerKnowledge by id and by knowledge
     *
     * @param int $ownerKnowledgeId
     * @param int $knowledgeId
     *
     * @return OwnerKnowledge
     */
    public function getByIdAndKnowledge($ownerKnowledgeId, $knowledgeId)
    {
        $knowledge = $this->knowledgeService->get($knowledgeId);

        return $this->ownerKnowledgeRepository->findByIdAndKnowledge($ownerKnowledgeId, $knowledge);
    }

    /**
     * Create and add an ownerKnowledge from an ownerKnowledgeResource
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $knowledgeId
     * @param int                    $ownerId
     *
     * @return OwnerKnowledge
     */
    public function createAndAdd(
        OwnerKnowledgeResource $ownerKnowledgeResource,
        $knowledgeId,
        $ownerId
    )
    {
        $ownerKnowledge = OwnerKnowledgeFactory::createFromResource($ownerKnowledgeResource);
        $ownerKnowledge->setOwner($this->userService->get($ownerId));
        $ownerKnowledge->setKnowledge($this->knowledgeService->get($knowledgeId));

        $metadata = array();
        foreach ($ownerKnowledgeResource->getMetadata() as $key => $value) {
            $md = KnowledgeMetadataFactory::create($key, $value);
            $md->setOwnerKnowledge($ownerKnowledge);
            $metadata[] = $md;
        }
        $ownerKnowledge->setMetadata(new ArrayCollection($metadata));

        return $this->add($ownerKnowledge);
    }

    /**
     * Save an owner knowledge given in form of an OwnerKnowledgeResource
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $ownerKnowledgeId
     * @param int                    $knowledgeId
     *
     * @return OwnerKnowledge
     */
    public function edit(
        OwnerKnowledgeResource $ownerKnowledgeResource,
        $ownerKnowledgeId,
        $knowledgeId = null
    )
    {
        $knowledge = null;
        if (!is_null($knowledgeId)) {
            $knowledge = $this->knowledgeService->get($knowledgeId);
        }

        $ownerKnowledge = $this->get($ownerKnowledgeId, $knowledge);
        $ownerKnowledge->setPublic($ownerKnowledgeResource->getPublic());

        return $this->save($ownerKnowledge);
    }

    /**
     * Add an owner knowledge
     *
     * @param OwnerKnowledge $ownerKnowledge
     *
     * @return OwnerKnowledge
     * @Transactional
     */
    public function add(OwnerKnowledge $ownerKnowledge)
    {
        $this->ownerKnowledgeRepository->insert($ownerKnowledge);

        return $ownerKnowledge;
    }

    /**
     * Save an owner knowledge
     *
     * @param OwnerKnowledge $ownerKnowledge
     *
     * @return OwnerKnowledge
     * @Transactional
     */
    public function save(OwnerKnowledge $ownerKnowledge)
    {
        return $this->ownerKnowledgeRepository->update($ownerKnowledge);
    }

    /**
     * Edit all the metadata of a knowledge
     *
     * @param int             $ownerKnowledgeId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    public function editMetadata($ownerKnowledgeId, ArrayCollection $metadatas)
    {
        $ownerKnowledge = $this->ownerKnowledgeRepository->find($ownerKnowledgeId);

        $this->metadataService->deleteAllByOwnerKnowledge($ownerKnowledgeId);

        $metadataCollection = array();
        foreach ($metadatas as $key => $value) {
            $md = KnowledgeMetadataFactory::create($key, $value);
            $md->setOwnerKnowledge($ownerKnowledge);
            $metadataCollection[] = $md;
        }
        $ownerKnowledge->setMetadata(new ArrayCollection($metadataCollection));

        return $this->save($ownerKnowledge)->getMetadata();
    }

    /**
     * Delete an owner knowledge
     *
     * @param $ownerKnowledgeId
     *
     * @Transactional
     */
    public function remove($ownerKnowledgeId)
    {
        $knowledge = $this->ownerKnowledgeRepository->find($ownerKnowledgeId);
        $this->ownerKnowledgeRepository->delete($knowledge);
    }
}
