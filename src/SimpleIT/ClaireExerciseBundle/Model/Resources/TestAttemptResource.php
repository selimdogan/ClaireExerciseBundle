<?php
namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class TestAttemptResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptResource
{
    /**
     * @const RESOURCE_NAME = 'Test Attempt'
     */
    const RESOURCE_NAME = 'Test Attempt';

    /**
     * @var integer $id Id of test attempt
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "test_attempt", "list"})
     */
    private $id;

    /**
     * @var \DateTime $createdAt
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({"details", "test_attempt", "list"})
     */
    private $createdAt;

    /**
     * @var int $test
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "test_attempt", "list"})
     */
    private $test;

    /**
     * @var int $user
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "test_attempt", "list"})
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
     * Set test
     *
     * @param int $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * Get test
     *
     * @return int
     */
    public function getTest()
    {
        return $this->test;
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
}
