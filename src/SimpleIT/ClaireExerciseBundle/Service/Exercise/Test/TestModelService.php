<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\TestModelResource;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel\ExerciseModelService;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModel;
use SimpleIT\ClaireExerciseBundle\Entity\TestModelFactory;
use SimpleIT\ClaireExerciseBundle\Entity\TestModelPositionFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\Test\TestModelRepository;
use SimpleIT\ClaireExerciseBundle\Service\User\UserService;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Class TestModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelService extends TransactionalService implements TestModelServiceInterface
{
    /**
     * @var TestModelRepository $testModelRepository
     */
    private $testModelRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var ExerciseModelService
     */
    private $exerciseModelService;

    /**
     * Set testModelRepository
     *
     * @param TestModelRepository $testModelRepository
     */
    public function setTestModelRepository($testModelRepository)
    {
        $this->testModelRepository = $testModelRepository;
    }

    /**
     * Set userService
     *
     * @param \SimpleIT\ClaireExerciseBundle\Service\User\UserService $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Set exerciseModelService
     *
     * @param \SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseModel\ExerciseModelService $exerciseModelService
     */
    public function setExerciseModelService($exerciseModelService)
    {
        $this->exerciseModelService = $exerciseModelService;
    }

    /**
     * Get a Test Model entity
     *
     * @param int $testModelId
     *
     * @return TestModel
     * @throws NonExistingObjectException
     */
    public function get($testModelId)
    {
        $testModel = $this->testModelRepository->find($testModelId);
        if (is_null($testModel)) {
            throw new NonExistingObjectException();
        }

        return $testModel;
    }

    /**
     * Get a list of Test Model
     *
     * @param CollectionInformation $collectionInformation The collection information
     *
     * @return PaginatorInterface
     */
    public function getAll($collectionInformation = null)
    {
        return $this->testModelRepository->findAllBy($collectionInformation);
    }

    /**
     * Create and add a test model from a resource
     *
     * @param TestModelResource $testModelResource
     * @param int               $authorId
     *
     * @return TestModel
     */
    public function createAndAdd(TestModelResource $testModelResource, $authorId)
    {
        $testModel = $this->createFromResource($testModelResource, $authorId);

        return $this->add($testModel);
    }

    /**
     * Create an ExerciseModel entity from a resource
     *
     * @param TestModelResource $testModelResource
     * @param int               $authorId
     *
     * @throws NoAuthorException
     * @return TestModel
     */
    public function createFromResource(TestModelResource $testModelResource, $authorId = null)
    {
        $testModel = TestModelFactory::createFromResource($testModelResource);

        if (!is_null($testModelResource->getAuthor())) {
            $authorId = $testModelResource->getAuthor();
        }
        if (is_null($authorId)) {
            throw new NoAuthorException();
        }
        $testModel->setAuthor(
            $this->userService->get($authorId)
        );

        $testModelPositions = array();
        foreach ($testModelResource->getExerciseModels() as $position => $oemId) {
            /** @var ExerciseModel $exerciseModel */
            $exerciseModel = $this->exerciseModelService->get($oemId);
            $testModelPositions[] = TestModelPositionFactory::create(
                $exerciseModel,
                $testModel,
                $position
            );
        }
        $testModel->setTestModelPositions(new ArrayCollection($testModelPositions));

        return $testModel;
    }

    /**
     * Add a test model from an entity
     *
     * @param TestModel $testModel
     *
     * @return TestModel
     * @Transactional
     */
    public function add(TestModel $testModel)
    {
        $this->testModelRepository->insert($testModel);

        return $testModel;
    }

    /**
     * Update an ExerciseResource object from a ResourceResource
     *
     * @param TestModelResource $testModelResource
     * @param TestModel         $testModel
     *
     * @throws NoAuthorException
     * @return TestModel
     */
    public function updateFromResource(TestModelResource $testModelResource, $testModel)
    {
        if (!is_null($testModelResource->getExerciseModels())) {
            $testModelPositions = array();
            foreach ($testModelResource->getExerciseModels() as $position => $oemId) {
                /** @var ExerciseModel $exerciseModel */
                $exerciseModel = $this->exerciseModelService->get($oemId);
                $testModelPositions[] = TestModelPositionFactory::create(
                    $exerciseModel,
                    $testModel,
                    $position
                );
            }
            $testModel->setTestModelPositions(new ArrayCollection($testModelPositions));

            // remove the previous positions
            $this->removePositions($testModel->getId());

        }

        if (!is_null($testModelResource->getTitle())) {
            $testModel->setTitle($testModelResource->getTitle());
        }

        return $testModel;
    }

    /**
     * Save a resource given in form of a ResourceResource
     *
     * @param TestModelResource $testModelResource
     * @param int               $testModelId
     *
     * @return TestModel
     */
    public function edit(TestModelResource $testModelResource, $testModelId)
    {
        $testModel = $this->get($testModelId);
        $testModel = $this->updateFromResource(
            $testModelResource,
            $testModel
        );

        return $this->save($testModel);
    }

    /**
     * Save a resource
     *
     * @param TestModel $testModel
     *
     * @return TestModel
     * @Transactional
     */
    public function save(TestModel $testModel)
    {
        return $this->testModelRepository->update($testModel);
    }

    /**
     * Remove all the positions for a test model
     *
     * @param int $testModelId
     */
    public function removePositions($testModelId)
    {
        // TODO: possible only if no test has been generated from this model
        $this->testModelRepository->deleteAllPositions($testModelId);
    }

    /**
     * Delete a test model
     *
     * @param $testModelId
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException
     */
    public function remove($testModelId)
    {
        try {
            $testModel = $this->testModelRepository->find($testModelId);
            $this->testModelRepository->delete($testModel);
            $this->em->flush();
        } catch (DBALException $dbale) {
            throw new EntityDeletionException('This entity is needed and cannot be deleted');
        }
    }
}
