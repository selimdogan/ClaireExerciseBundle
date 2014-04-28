<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice;

use JMS\Serializer\Annotation as Serializer;

/**
 * An proposition contains the text of the proposition, if it is a good one and if it has
 * been ticked by the user (if propositioned).
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Proposition
{
    /**
     * @var string $text
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $text;

    /**
     * @var boolean $right
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $right;

    /**
     * @var boolean $ticked
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "corrected"})
     */
    private $ticked;

    /**
     * Constructor
     *
     * @param string  $text
     * @param boolean $right
     * @param boolean $ticked
     */
    function __construct($text, $right, $ticked)
    {
        $this->text = $text;
        $this->right = $right;
        $this->ticked = $ticked;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Is right
     *
     * @return boolean
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set right
     *
     * @param boolean $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * Is ticked
     *
     * @return boolean
     */
    public function getTicked()
    {
        return $this->ticked;
    }

    /**
     * Set ticked
     *
     * @param boolean $ticked
     */
    public function setTicked($ticked)
    {
        $this->ticked = $ticked;
    }
}
