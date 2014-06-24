<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\Test;

use Doctrine\Common\Collections\Collection;
use Claroline\CoreBundle\Entity\User;

/**
 * Class Test for the test sessions models containing exercise models.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Collection
     */
    private $testModelPositions;

    /**
     * @var User
     */
    private $author;

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
     * Set testModelPositions
     *
     * @param Collection $testModelPositions
     */
    public function setTestModelPositions($testModelPositions)
    {
        $this->testModelPositions = $testModelPositions;
    }

    /**
     * Get testModelPositions
     *
     * @return Collection
     */
    public function getTestModelPositions()
    {
        return $this->testModelPositions;
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
}
