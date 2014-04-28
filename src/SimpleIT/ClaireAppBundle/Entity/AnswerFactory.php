<?php

namespace SimpleIT\ExerciseBundle\Entity;

use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Item;

/**
 * Class to manage the creation of learner answers
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class AnswerFactory
{
    /**
     * Create a new Answer entity from a serialized array
     *
     * @param string  $content
     * @param Item    $item
     * @param Attempt $attempt
     *
     * @return Answer
     */
    public static function create($content, Item $item, $attempt = null)
    {
        $answer = new Answer();
        $answer->setContent($content);
        $answer->setItem($item);
        if (!is_null($attempt)) {
            $answer->setAttempt($attempt);
        }

        return $answer;
    }
}
