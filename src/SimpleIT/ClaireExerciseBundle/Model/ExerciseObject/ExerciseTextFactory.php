<?php

namespace SimpleIT\ClaireExerciseBundle\Model\ExerciseObject;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseObject\ExerciseTextObject;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\TextResource;

/**
 * Factory to create a text object for an exercise.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseTextFactory
{
    /**
     * Create a text object for exercise from an exercise resource.
     *
     * @param TextResource $res The resource
     *
     * @return ExerciseTextObject
     */
    public static function createFromCommonResource(TextResource $res)
    {
        $textObj = new ExerciseTextObject();
        $textObj->setText($res->getText());

        return $textObj;
    }

    /**
     * Create a text object from a string
     *
     * @param string $text
     *
     * @return ExerciseTextObject
     */
    public static function createFromText($text)
    {
        $textObj = new ExerciseTextObject();
        $textObj->setText($text);

        return $textObj;
    }

    /**
     * Create one text object from two
     *
     * @param ExerciseTextObject $t1
     * @param ExerciseTextObject $t2
     *
     * @return ExerciseTextObject
     */
    public static function createFromTwoObjects(
        ExerciseTextObject $t1,
        ExerciseTextObject $t2
    )
    {
        $textObj = new ExerciseTextObject();
        $text = $t1->getText();
        $text .= $t2->getText();
        $textObj->setText($text);

        return $textObj;
    }
}
