<?php

namespace SimpleIT\ExerciseBundle\Entity\Test;

use Doctrine\Common\Collections\Collection;

/**
 * Class Test for the test sessions containing stored exercises.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Test
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Collection
     */
    private $testPositions;

    /**
     * @var Collection
     */
    private $testAttempts;

    /**
     * @var TestModel
     */
    private $testModel;

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set testModel
     *
     * @param TestModel $testModel
     */
    public function setTestModel($testModel)
    {
        $this->testModel = $testModel;
    }

    /**
     * Get testModel
     *
     * @return TestModel
     */
    public function getTestModel()
    {
        return $this->testModel;
    }

    /**
     * Set testPositions
     *
     * @param Collection $testPositions
     */
    public function setTestPositions($testPositions)
    {
        $this->testPositions = $testPositions;
    }

    /**
     * Get testPositions
     *
     * @return Collection
     */
    public function getTestPositions()
    {
        return $this->testPositions;
    }

    /**
     * Set testAttempts
     *
     * @param Collection $testAttempts
     */
    public function setTestAttempts($testAttempts)
    {
        $this->testAttempts = $testAttempts;
    }

    /**
     * Get testAttempts
     *
     * @return Collection
     */
    public function getTestAttempts()
    {
        return $this->testAttempts;
    }
}
