<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge;

/**
 * Knowledge Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Metadata
{
    /**
     * @var OwnerKnowledge
     */
    private $ownerKnowledge;

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
     * Set ownerKnowledge
     *
     * @param OwnerKnowledge $ownerKnowledge
     */
    public function setOwnerKnowledge($ownerKnowledge)
    {
        $this->ownerKnowledge = $ownerKnowledge;
    }

    /**
     * Get ownerKnowledge
     *
     * @return OwnerKnowledge
     */
    public function getOwnerKnowledge()
    {
        return $this->ownerKnowledge;
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
