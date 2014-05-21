<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;

/**
 * Claire exercise resource entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResource
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $type;

    /**
     * @var  User
     */
    private $author;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var Collection
     */
    private $children;

    /**
     * @var ExerciseResource
     */
    private $parent;

    /**
     * @var Collection
     */
    private $forkedBy;

    /**
     * @var ExerciseResource
     */
    private $forkFrom;

    /**
     * @var bool
     */
    private $public;

    /**
     * @var boolean $archived
     */
    private $archived;

    /**
     * @var Collection
     */
    private $metadata;

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
        $this->metadata = new ArrayCollection();
        $this->requiredExerciseResources = new ArrayCollection();
        $this->requiredKnowledges= new ArrayCollection();
        $this->forkedBy= new ArrayCollection();
        $this->children= new ArrayCollection();
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set author
     *
     * @param User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
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
     * Set archived
     *
     * @param boolean $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Set children
     *
     * @param \Doctrine\Common\Collections\Collection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
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
     * Set forkedBy
     *
     * @param \Doctrine\Common\Collections\Collection $forkedBy
     */
    public function setForkedBy($forkedBy)
    {
        $this->forkedBy = $forkedBy;
    }

    /**
     * Get forkedBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForkedBy()
    {
        return $this->forkedBy;
    }

    /**
     * Set metadata
     *
     * @param \Doctrine\Common\Collections\Collection $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set owner
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\User\User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\User\User
     */
    public function getOwner()
    {
        return $this->owner;
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

    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
}
