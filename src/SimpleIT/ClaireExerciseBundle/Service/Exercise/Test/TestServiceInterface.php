<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\Test;

use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\Test\Test;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Interface for service which manages the tests
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface TestServiceInterface
{
    /**
     * Find a test by its id
     *
     * @param int $testId Test Id
     *
     * @throws NonExistingObjectException
     * @return Test
     */
    public function get($testId);

    /**
     * Add a new test to the database. This test is linked to the test model.
     *
     * @param int $testModelId
     *
     * @return Test
     */
    public function add($testModelId);

    /**
     * Get all the tests
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $testModelId
     *
     * @return PaginatorInterface
     */
    public function getAll(CollectionInformation $collectionInformation, $testModelId = null);
}
