<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;

/**
 * Claire exercise model entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModel extends SharedEntity
{
    /**
     * @var boolean $draft
     */
    private $draft;

    /**
     * @var boolean $complete
     */
    private $complete;

    /**
     * @var ExerciseModel
     */
    private $parent;

    /**
     * @var ExerciseModel
     */
    private $forkFrom;

    /**
     * @var Collection
     */
    private $requiredExerciseResources;

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
        $this->requiredExerciseResources = new ArrayCollection();
        $this->requiredKnowledges = new ArrayCollection();
    }

    /**
     * Set complete
     *
     * @param boolean $complete
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    }

    /**
     * Get complete
     *
     * @return boolean
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Set draft
     *
     * @param boolean $draft
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;
    }

    /**
     * Get draft
     *
     * @return boolean
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set requiredExerciseResources
     *
     * @param \Doctrine\Common\Collections\Collection $requiredExerciseResources
     */
    public function setRequiredExerciseResources($requiredExerciseResources)
    {
        $this->requiredExerciseResources = $requiredExerciseResources;
    }

    /**
     * Get requiredExerciseResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequiredExerciseResources()
    {
        return $this->requiredExerciseResources;
    }

    /**
     * Set requiredKnowledges
     *
     * @param \Doctrine\Common\Collections\Collection $requiredKnowledges
     */
    public function setRequiredKnowledges($requiredKnowledges)
    {
        $this->requiredKnowledges = $requiredKnowledges;
    }

    /**
     * Get requiredKnowledges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequiredKnowledges()
    {
        return $this->requiredKnowledges;
    }

    /**
     * Set parent
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set forkFrom
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel $forkFrom
     */
    public function setForkFrom($forkFrom)
    {
        $this->forkFrom = $forkFrom;
    }

    /**
     * Get forkFrom
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel
     */
    public function getForkFrom()
    {
        return $this->forkFrom;
    }
}
