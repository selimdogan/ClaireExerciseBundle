<?php

namespace SimpleIT\ExerciseBundle\Entity\ExerciseModel;

use Doctrine\Common\Collections\Collection;
use SimpleIT\CommonBundle\Entity\User;

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
     * @var boolean $complete
     */
    private $complete;

    /**
     * @var User
     */
    private $author;

    /**
     * @var Collection
     */
    private $requiredExerciseResources;

    /**
     * @var Collection
     */
    private $requiredKnowledges;

    /**
     * @var Collection
     */
    private $ownerExerciseModels;

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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set requiredExerciseResources
     *
     * @param Collection $requiredExerciseResources
     */
    public function setRequiredExerciseResources($requiredExerciseResources)
    {
        $this->requiredExerciseResources = $requiredExerciseResources;
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
     * Set ownerExerciseModels
     *
     * @param Collection $ownerExerciseModels
     */
    public function setOwnerExerciseModels($ownerExerciseModels)
    {
        $this->ownerExerciseModels = $ownerExerciseModels;
    }

    /**
     * Get ownerExerciseModels
     *
     * @return Collection
     */
    public function getOwnerExerciseModels()
    {
        return $this->ownerExerciseModels;
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
     * Set requiredKnowledges
     *
     * @param Collection $requiredKnowledges
     */
    public function setRequiredKnowledges($requiredKnowledges)
    {
        $this->requiredKnowledges = $requiredKnowledges;
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
}
