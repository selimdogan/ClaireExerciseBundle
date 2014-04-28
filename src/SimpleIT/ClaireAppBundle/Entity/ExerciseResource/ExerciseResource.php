<?php

namespace SimpleIT\ExerciseBundle\Entity\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\CommonBundle\Entity\User;

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
    private $ownerResources;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metadata = new ArrayCollection();
        $this->requiredExerciseResources = new ArrayCollection();
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
     * Set ownerResources
     *
     * @param Collection $ownerResources
     */
    public function setOwnerResources($ownerResources)
    {
        $this->ownerResources = $ownerResources;
    }

    /**
     * Get ownerResources
     *
     * @return Collection
     */
    public function getOwnerResources()
    {
        return $this->ownerResources;
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
}
