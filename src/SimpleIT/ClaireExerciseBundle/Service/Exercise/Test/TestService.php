<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\Test;

use SimpleIT\ClaireExerciseBundle\Entity\Test\Test;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModelPosition;
use SimpleIT\ClaireExerciseBundle\Entity\TestFactory;
use SimpleIT\ClaireExerciseBundle\Entity\TestPositionFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\Test\TestRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\CreatedExercise\StoredExerciseServiceInterface;
use SimpleIT\ClaireExerciseBundle\Service\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Service which manages the tests
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestService extends TransactionalService implements TestServiceInterface
{

    /**
     * @var TestRepository $testRepository
     */
    private $testRepository;

    /**
     * @var TestModelService
     */
    private $testModelService;

    /**
     * @var  StoredExerciseServiceInterface
     */
    private $storedExerciseService;

    /**
     * Set testRepository
     *
     * @param TestRepository $testRepository
     */
    public function setTestRepository($testRepository)
    {
        $this->testRepository = $testRepository;
    }

    /**
     * Set testModelService
     *
     * @param TestModelService $testModelService
     */
    public function setTestModelService($testModelService)
    {
        $this->testModelService = $testModelService;
    }

    /**
     * Set storedExerciseService
     *
     * @param StoredExerciseServiceInterface $storedExerciseService
     */
    public function setStoredExerciseService($storedExerciseService)
    {
        $this->storedExerciseService = $storedExerciseService;
    }

    /**
     * Find a test by its id
     *
     * @param int $testId Test Id
     *
     * @throws NonExistingObjectException
     * @return Test
     */
    public function get($testId)
    {
        $test = $this->testRepository->find($testId);
        if (is_null($test)) {
            throw new NonExistingObjectException();
        }

        return $test;
    }

    /**
     * Add a new test to the database. This test is linked to the test model.
     *
     * @param int $testModelId
     *
     * @return Test
     */
    public function add($testModelId)
    {
        $testModel = $this->testModelService->get($testModelId);

        $testPositions = array();
        foreach ($testModel->getTestModelPositions() as $modelPosition) {
            /** @var TestModelPosition $modelPosition */
            $oemId = $modelPosition->getExerciseModel()->getId();
            $exercise = $this->storedExerciseService->addByExerciseModel($oemId);
            $testPositions[] = TestPositionFactory::create(
                $exercise,
                $modelPosition->getPosition()
            );
        }

        $test = TestFactory::create($testPositions, $testModel);

        $this->em->persist($test);
        $this->em->flush();

        return $test;
    }

    /**
     * Get all the tests
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $testModelId
     *
     * @return array
     */
    public function getAll($collectionInformation = null, $testModelId = null)
    {
        $testModel = null;

        if (!is_null($testModelId)) {
            $testModel = $this->testModelService->get($testModelId);
        }

        return $this->testRepository->findAllBy($collectionInformation, $testModel);
    }
}
