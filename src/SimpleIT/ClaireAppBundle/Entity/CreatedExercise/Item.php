<?php

namespace SimpleIT\ExerciseBundle\Entity\CreatedExercise;

/**
 * Claire answer entity for a stored exercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Item
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var  StoredExercise
     */
    private $storedExercise;

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
     * Set storedExercise
     *
     * @param StoredExercise $storedExercise
     */
    public function setStoredExercise($storedExercise)
    {
        $this->storedExercise = $storedExercise;
    }

    /**
     * Get storedExercise
     *
     * @return StoredExercise
     */
    public function getStoredExercise()
    {
        return $this->storedExercise;
    }
}
