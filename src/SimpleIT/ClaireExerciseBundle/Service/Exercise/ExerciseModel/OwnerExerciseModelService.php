<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerExerciseModelResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModelMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Entity\OwnerExerciseModelFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerExerciseModelResourceFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseModel\OwnerExerciseModelRepository;
use SimpleIT\ClaireExerciseBundle\Service\User\UserServiceInterface;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Class OwnerExerciseModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelService extends TransactionalService implements OwnerExerciseModelServiceInterface
{
    /**
     * @var OwnerExerciseModelRepository
     */
    private $ownerExerciseModelRepository;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var ExerciseModelServiceInterface
     */
    private $exerciseModelService;

    /**
     * @var MetadataServiceInterface
     */
    private $metadataService;

    /**
     * Set ownerExerciseModelRepository
     *
     * @param OwnerExerciseModelRepository $ownerExerciseModelRepository
     */
    public function setOwnerExerciseModelRepository($ownerExerciseModelRepository)
    {
        $this->ownerExerciseModelRepository = $ownerExerciseModelRepository;
    }

    /**
     * Set exerciseModelService
     *
     * @param ExerciseModelServiceInterface $exerciseModelService
     */
    public function setExerciseModelService($exerciseModelService)
    {
        $this->exerciseModelService = $exerciseModelService;
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
     * Set metadataService
     *
     * @param MetadataServiceInterface $metadataService
     */
    public function setMetadataService($metadataService)
    {
        $this->metadataService = $metadataService;
    }

    /**
     * Get an Owner Exercise Model entity
     *
     * @param int $ownerExerciseModelId
     *
     * @return OwnerExerciseModel
     * @throws NonExistingObjectException
     */
    public function get($ownerExerciseModelId)
    {
        return $this->ownerExerciseModelRepository->find($ownerExerciseModelId);
    }

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
        $collectionInformation = null,
        $ownerId = null,
        $exerciseModelId = null
    )
    {
        $model = null;
        if (!is_null($exerciseModelId)) {
            $model = $this->exerciseModelService->get($exerciseModelId);
        }

        $owner = null;
        if (!is_null($ownerId)) {
            $owner = $this->userService->get($ownerId);
        }

        return $this->ownerExerciseModelRepository->findAll($collectionInformation, $owner, $model);
    }

    /**
     * Get an OwnerExerciseModel by id and by model
     *
     * @param int $ownerExerciseModelId
     * @param int $exerciseModelId
     *
     * @return OwnerExerciseModel
     */
    public function getByIdAndModel($ownerExerciseModelId, $exerciseModelId)
    {
        $exerciseModel = $this->exerciseModelService->get($exerciseModelId);

        return $this->ownerExerciseModelRepository->findByIdAndModel(
            $ownerExerciseModelId,
            $exerciseModel
        );
    }

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
    )
    {
        $ownerExerciseModel = OwnerExerciseModelFactory::createFromResource(
            $ownerExerciseModelResource
        );
        $ownerExerciseModel->setOwner($this->userService->get($ownerId));
        $ownerExerciseModel->setExerciseModel($this->exerciseModelService->get($modelId));

        $metadata = array();
        foreach ($ownerExerciseModelResource->getMetadata() as $key => $value) {
            $md = ExerciseModelMetadataFactory::create($key, $value);
            $md->setOwnerExerciseModel($ownerExerciseModel);
            $metadata[] = $md;
        }
        $ownerExerciseModel->setMetadata(new ArrayCollection($metadata));

        return $this->add($ownerExerciseModel);
    }

    /**
     * Add an ownerExerciseModel
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     *
     * @return OwnerExerciseModel
     * @Transactional
     */
    public function add(OwnerExerciseModel $ownerExerciseModel)
    {
        $this->ownerExerciseModelRepository->insert($ownerExerciseModel);

        return $ownerExerciseModel;
    }

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
    )
    {
        $model = null;
        if (!is_null($modelId)) {
            $model = $this->exerciseModelService->get($modelId);
        }

        $ownerExerciseModel = $this->get($ownerExerciseModelId, $model);

        if (!is_null($ownerExerciseModelResource->getPublic())) {
            $ownerExerciseModel->setPublic($ownerExerciseModelResource->getPublic());
        }

        if (!is_null($ownerExerciseModelResource->getExerciseModel())) {
            $newModel = $this->exerciseModelService->get(
                $ownerExerciseModelResource->getExerciseModel()
            );
            $ownerExerciseModel->setExerciseModel($newModel);
        }

        return $this->save($ownerExerciseModel);
    }

    /**
     * Save an owner exercise model
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     *
     * @return OwnerExerciseModel
     * @Transactional
     */
    public function save(OwnerExerciseModel $ownerExerciseModel)
    {
        return $this->ownerExerciseModelRepository->update($ownerExerciseModel);
    }

    /**
     * Delete an owner exercise model
     *
     * @param $ownerExerciseModelId
     *
     * @Transactional
     */
    public function remove($ownerExerciseModelId)
    {
        $ownerExerciseModel = $this->ownerExerciseModelRepository->find($ownerExerciseModelId);
        $this->ownerExerciseModelRepository->delete($ownerExerciseModel);
    }

    /**
     * Edit all the metadata of an owner exercise model
     *
     * @param int             $ownerExerciseModelId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    public function editMetadata($ownerExerciseModelId, ArrayCollection $metadatas)
    {
        $ownerExerciseModel = $this->ownerExerciseModelRepository->find($ownerExerciseModelId);

        $this->metadataService->deleteAllByOwnerExerciseModel($ownerExerciseModelId);

        $metadataCollection = array();
        foreach ($metadatas as $key => $value) {
            $md = ExerciseModelMetadataFactory::create($key, $value);
            $md->setOwnerExerciseModel($ownerExerciseModel);
            $metadataCollection[] = $md;
        }
        $ownerExerciseModel->setMetadata(new ArrayCollection($metadataCollection));

        return $this->save($ownerExerciseModel)->getMetadata();
    }

    /**
     * Get an OwnerExerciseModel by id and by owner
     *
     * @param int $ownerExerciseModelId
     * @param int $ownerId
     *
     * @return OwnerExerciseModel
     */
    public function getByIdAndOwner($ownerExerciseModelId, $ownerId)
    {
        $owner = $this->userService->get($ownerId);

        return $this->ownerExerciseModelRepository->findByIdAndOwner
            (
                $ownerExerciseModelId,
                $owner
            );
    }
}
