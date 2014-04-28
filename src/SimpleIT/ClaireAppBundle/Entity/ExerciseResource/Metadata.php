<?php

namespace SimpleIT\ExerciseBundle\Entity\ExerciseResource;

/**
 * Exercise Resource Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Metadata
{
    /**
     * @var OwnerResource
     */
    private $ownerResource;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set ownerResource
     *
     * @param OwnerResource $ownerResource
     */
    public function setOwnerResource($ownerResource)
    {
        $this->ownerResource = $ownerResource;
    }

    /**
     * Get ownerResource
     *
     * @return OwnerResource
     */
    public function getOwnerResource()
    {
        return $this->ownerResource;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}
