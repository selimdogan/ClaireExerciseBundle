<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common;

use JMS\Serializer\Annotation as Serializer;

/**
 * Abstract class for the item in their final form.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "item_type", map = {
 *    "group-items": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\GroupItems\Item",
 *    "pair-items": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\PairItems\Item",
 *    "order-items": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\OrderItems\Item",
 *    "multiple-choice-question": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice\Question",
 *    "open-ended-question": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\OpenEndedQuestion\Question"
 * })
 */
abstract class CommonItem
{
    /**
     * @var string $comment A comment linked with the question which will be displayed after the
     * correction
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    protected $comment;

    /**
     * @var boolean
     * @Serializer\Exclude
     */
    protected $allRight;

    /**
     * @var string
     * @Serializer\Exclude
     */
    protected $explanation;

    /**
     * Set allRight
     *
     * @param boolean $allRight
     */
    public function setAllRight($allRight)
    {
        $this->allRight = $allRight;
    }

    /**
     * Get allRight
     *
     * @return boolean
     */
    public function getAllRight()
    {
        return $this->allRight;
    }

    /**
     * Set explanation
     *
     * @param string $explanation
     */
    public function setExplanation($explanation)
    {
        $this->explanation = $explanation;
    }

    /**
     * Get explanation
     *
     * @return string
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}