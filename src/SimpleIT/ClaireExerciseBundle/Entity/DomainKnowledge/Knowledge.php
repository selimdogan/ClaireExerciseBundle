<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\Common\SharedEntity;

/**
 * Claire exercise knowledge entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Knowledge extends SharedEntity
{
    /**
     * @var Knowledge
     */
    private $parent;

    /**
     * @var Knowledge
     */
    private $forkFrom;

    /**
     * @var Collection
     */
    private $requiredKnowledges;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->requiredKnowledges = new ArrayCollection();
    }

    /**
     * Get requiredKnowledges
     *
     * @return Collection
     */
    public function getRequiredKnowledges()
    {
        return $this->requiredKnowledges;
    }

    /**
     * Set requiredKnowledges
     *
     * @param Collection $requiredKnowledges
     */
    public function setRequiredKnowledges($requiredKnowledges)
    {
        $this->requiredKnowledges = $requiredKnowledges;
    }

    /**
     * Add a Required  Knowledge
     *
     * @param Knowledge $requiredKnowledge Required  Knowledge
     */
    public function addRequiredKnowledge($requiredKnowledge)
    {
        $this->requiredKnowledges->add($requiredKnowledge);
    }

    /**
     * Remove a Required  Knowledge
     *
     * @param Knowledge $requiredKnowledge Required  Knowledge
     */
    public function removeRequiredKnowledge($requiredKnowledge)
    {
        $this->requiredKnowledges->removeElement($requiredKnowledge);
    }

    /**
     * Set forkFrom
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge $forkFrom
     */
    public function setForkFrom($forkFrom)
    {
        $this->forkFrom = $forkFrom;
    }

    /**
     * Get forkFrom
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge
     */
    public function getForkFrom()
    {
        return $this->forkFrom;
    }

    /**
     * Set parent
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge
     */
    public function getParent()
    {
        return $this->parent;
    }
}
