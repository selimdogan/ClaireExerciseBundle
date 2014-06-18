<?php

namespace SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\MultipleChoice;

use SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\Common\CommonModelFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\MultipleChoice\Model;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\MultipleChoice\QuestionBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;

/**
 * This class manages the creation of instances of multiple choice Model.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ModelFactory extends CommonModelFactory
{
    /**
     * Check the number of questions in each block
     *
     * @param Model $model
     *
     * @return bool
     */
    public static function validateModel(Model $model)
    {
        foreach ($model->getQuestionBlocks() as $block) {
            /** @var QuestionBlock $block */
            if (
                $block->isList() &&
                $block->getNumberOfOccurrences() > count($block->getResources())
            ) {
                return false;
            }
        }

        return true;
    }
}
