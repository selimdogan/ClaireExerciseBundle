<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseObject;

use JMS\Serializer\Annotation as Serializer;

/**
 * A picture object in an exercise.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExercisePictureObject extends ExerciseObject
{
    /**
     * @var string $source The source of the picture
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage", "exercise", "exercise_storage"})
     */
    private $source;

    /**
     * Get source
     *
     * @return string The source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set source
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}
