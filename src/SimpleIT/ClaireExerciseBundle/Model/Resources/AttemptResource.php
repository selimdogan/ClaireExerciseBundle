<?php
namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class AttemptResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptResource
{
    /**
     * @const RESOURCE_NAME = 'Attempt'
     */
    const RESOURCE_NAME = 'Attempt';

    /**
     * @var integer $id Id of attempt
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "attempt", "list"})
     */
    private $id;

    /**
     * @var \DateTime $createdAt
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({"details", "attempt", "list"})
     */
    private $createdAt;

    /**
     * @var int $exercise
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "attempt", "list"})
     */
    private $exercise;

    /**
     * @var int $testAttempt
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "attempt", "list"})
     */
    private $testAttempt;

    /**
     * @var int $user
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "attempt", "list"})
     */
    private $user;

    /**
     * @var int $position
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "attempt", "list"})
     */
    private $position;

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
     * @param int $exercise
     */
    public function setExercise($exercise)
    {
        $this->exercise = $exercise;
    }

    /**
     * Get exercise
     *
     * @return int
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
     * @param int $testAttempt
     */
    public function setTestAttempt($testAttempt)
    {
        $this->testAttempt = $testAttempt;
    }

    /**
     * Get testAttempt
     *
     * @return int
     */
    public function getTestAttempt()
    {
        return $this->testAttempt;
    }

    /**
     * Set user
     *
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return int
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
