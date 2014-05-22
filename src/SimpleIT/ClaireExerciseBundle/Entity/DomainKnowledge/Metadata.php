<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge;

use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata as BaseMetadata;

/**
 * Knowledge Metadata entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Metadata extends BaseMetadata
{
    /**
     * @var Knowledge
     */
    private $knowledge;

    /**
     * Set knowledge
     *
     * @param Knowledge $knowledge
     */
    public function setKnowledge($knowledge)
    {
        $this->knowledge = $knowledge;
    }

    /**
     * Get knowledge
     *
     * @return Knowledge
     */
    public function getKnowledge()
    {
        return $this->knowledge;
    }

    /**
     * Set the knowledge
     *
     * @param Knowledge $entity
     */
    public function setEntity($entity)
    {
        $this->knowledge = $entity;
    }
}
