<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;

/**
 * Claire exercise model entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $content
     */
    private $content;

    /**
     * @var boolean $draft
     */
    private $draft;

    /**
     * @var boolean $archived
     */
    private $archived;

    /**
     * @var boolean $complete
     */
    private $complete;

    /**
     * @var User
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
     * @var ExerciseModel
     */
    private $parent;

    /**
     * @var Collection
     */
    private $forkedBy;

    /**
     * @var ExerciseModel
     */
    private $forkFrom;

    /**
     * @var bool
     */
    private $public;

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
        $this->requiredKnowledges = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * Set author
     *
     * @param \SimpleIT\ClaireExerciseBundle\Entity\User\User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return \SimpleIT\ClaireExerciseBundle\Entity\User\User
     */
    public function getAuthor()
    {
        return $this->author;
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
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
}
