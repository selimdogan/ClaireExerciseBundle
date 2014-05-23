<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;

/**
 * Claire exercise resource entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResource extends SharedEntity
{
    /**
     * @var ExerciseResource
     */
    protected $parent;

    /**
     * @var ExerciseResource
     */
    protected $forkFrom;

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
     * Get requiredExerciseResources
     *
     * @return Collection
     */
    public function getRequiredExerciseResources()
    {
        return $this->requiredExerciseResources;
    }

    /**
     * Set requiredExerciseResources
     *
     * @param Collection $requiredExerciseResources
     */
    public function setRequiredExerciseResources($requiredExerciseResources)
    {
        $this->requiredExerciseResources = $requiredExerciseResources;
    }

    /**
     * Add a Required Exercise Resource
     *
     * @param ExerciseResource $requiredExerciseResource Required Exercise Resource
     */
    public function addRequiredExerciseResource($requiredExerciseResource)
    {
        $this->requiredExerciseResources->add($requiredExerciseResource);
    }

    /**
     * Remove a Required Exercise Resource
     *
     * @param ExerciseResource $requiredExerciseResource Required Exercise Resource
     */
    public function removeRequiredExerciseResource($requiredExerciseResource)
    {
        $this->requiredExerciseResources->removeElement($requiredExerciseResource);
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
     * Set forkFrom
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource $forkFrom
     */
    public function setForkFrom($forkFrom)
    {
        $this->forkFrom = $forkFrom;
    }

    /**
     * Get forkFrom
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource
     */
    public function getForkFrom()
    {
        return $this->forkFrom;
    }

    /**
     * Set parent
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource
     */
    public function getParent()
    {
        return $this->parent;
    }
}
