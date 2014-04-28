<?php

namespace SimpleIT\ExerciseBundle\Service\ExerciseCreation;

use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;

/**
 * Service which manages the exercise generation
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface ExerciseServiceInterface
{
    /**
     * Correct an item from an exercise
     *
     * @param Answer $answer Answer
     *
     * @return Item The corrected item
     * @throws \Exception
     */
    public function correctItem(Answer $answer);

    /**
     * Generate an exercise from the id of the model
     *
     * @param OwnerExerciseModel $ownerExerciseModel
     *
     * @return CommonExercise The generated exercise
     */
    public function generateExercise($ownerExerciseModel);

    /**
     * Validate learner's answer format
     *
     * @param Item           $item
     * @param AnswerResource $answerResource
     */
    public function validateAnswer(Item $item, AnswerResource $answerResource);
}
