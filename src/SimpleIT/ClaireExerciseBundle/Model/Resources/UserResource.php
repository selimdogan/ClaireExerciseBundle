<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class AttemptResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class UserResource
{
    /**
     * @const RESOURCE_NAME = 'User'
     */
    const RESOURCE_NAME = 'User';

    /**
     * @var integer $id
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details"})
     */
    private $id;

    /**
     * @var string $firstName
     * @Serializer\Type("string")
     * @Serializer\Groups({"details"})
     */
    private $firstName;

    /**
     * @var string $lastName
     * @Serializer\Type("string")
     * @Serializer\Groups({"details"})
     */
    private $lastName;

    /**
     * @var string $userName
     * @Serializer\Type("string")
     * @Serializer\Groups({"details"})
     */
    private $userName;

    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
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
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set userName
     *
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }
}
