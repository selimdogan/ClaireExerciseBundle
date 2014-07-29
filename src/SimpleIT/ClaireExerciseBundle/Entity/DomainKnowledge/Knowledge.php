<?php
/*
 * This file is part of CLAIRE.
 *
 * CLAIRE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CLAIRE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CLAIRE. If not, see <http://www.gnu.org/licenses/>
 */

namespace SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;

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
    protected $parent;

    /**
     * @var Knowledge
     */
    protected $forkFrom;

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
