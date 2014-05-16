<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\Test;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\Test\TestModel;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface for class TestModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface TestModelServiceInterface
{
    /**
     * Get a Test Model entity
     *
     * @param int $testModelId
     *
     * @return TestModel
     * @throws NonExistingObjectException
     */
    public function get($testModelId);

    /**
     * Get a list of Test Model
     *
     * @param CollectionInformation $collectionInformation The collection information
     *
     * @return PaginatorInterface
     */
    public function getAll($collectionInformation = null);
}
