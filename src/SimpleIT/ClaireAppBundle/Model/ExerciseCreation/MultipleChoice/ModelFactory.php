<?php

namespace SimpleIT\ExerciseBundle\Model\ExerciseCreation\MultipleChoice;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\Model;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\QuestionBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ExerciseBundle\Model\ExerciseCreation\Common\CommonModelFactory;

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
