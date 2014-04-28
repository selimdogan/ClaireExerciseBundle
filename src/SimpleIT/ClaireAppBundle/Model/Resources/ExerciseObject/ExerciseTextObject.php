<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject;

use JMS\Serializer\Annotation as Serializer;

/**
 * A text object in an exercise.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseTextObject extends ExerciseObject
{
    /**
     * @var string $text The text
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage", "exercise_storage", "exercise"})
     */
    private $text;

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
}
