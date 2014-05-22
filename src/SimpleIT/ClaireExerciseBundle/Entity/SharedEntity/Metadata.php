<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\SharedEntity;

/**
 * Knowledge Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class Metadata
{
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

    /**
     * Set the entity
     *
     * @param SharedEntity $entity
     */
    abstract public function setEntity($entity);
}
