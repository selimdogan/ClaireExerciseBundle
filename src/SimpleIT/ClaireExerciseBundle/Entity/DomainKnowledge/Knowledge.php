<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;

/**
 * Claire exercise knowledge entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Knowledge
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
    private $requiredKnowledges;

    /**
     * @var Collection
     */
    private $ownerKnowledges;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metadata = new ArrayCollection();
        $this->requiredKnowledges = new ArrayCollection();
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
     * Set ownerKnowledges
     *
     * @param Collection $ownerKnowledges
     */
    public function setOwnerKnowledges($ownerKnowledges)
    {
        $this->ownerKnowledges = $ownerKnowledges;
    }

    /**
     * Get ownerKnowledges
     *
     * @return Collection
     */
    public function getOwnerKnowledges()
    {
        return $this->ownerKnowledges;
    }
}
