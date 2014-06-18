<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Entity\Test\TestAttempt;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;

/**
 * Claire attempt entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Attempt
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var StoredExercise
     */
    private $exercise;

    /**
     * @var TestAttempt
     */
    private $testAttempt;

    /**
     * @var int
     */
    private $position;

    /**
     * @var User
     */
    private $user;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set exercise
     *
     * @param StoredExercise $exercise
     */
    public function setExercise($exercise)
    {
        $this->exercise = $exercise;
    }

    /**
     * Get exercise
     *
     * @return StoredExercise
     */
    public function getExercise()
    {
        return $this->exercise;
    }

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
     * Set testAttempt
     *
     * @param TestAttempt $testAttempt
     */
    public function setTestAttempt($testAttempt)
    {
        $this->testAttempt = $testAttempt;
    }

    /**
     * Get testAttempt
     *
     * @return TestAttempt
     */
    public function getTestAttempt()
    {
        return $this->testAttempt;
    }

    /**
     * Set user
     *
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set position
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
