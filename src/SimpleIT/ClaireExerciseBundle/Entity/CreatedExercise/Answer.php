<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise;

/**
 * Claire answer entity for a stored exercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Answer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Item
     */
    private $item;

    /**
     * @var string
     */
    private $content;

    /**
     * @var Attempt
     */
    private $attempt;

    /**
     * @var float
     */
    private $mark;

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
     * Get item
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set item
     *
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * Set attempt
     *
     * @param Attempt $attempt
     */
    public function setAttempt($attempt)
    {
        $this->attempt = $attempt;
    }

    /**
     * Get attempt
     *
     * @return Attempt
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * Set mark
     *
     * @param float $mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
    }

    /**
     * Get mark
     *
     * @return float
     */
    public function getMark()
    {
        return $this->mark;
    }
}
