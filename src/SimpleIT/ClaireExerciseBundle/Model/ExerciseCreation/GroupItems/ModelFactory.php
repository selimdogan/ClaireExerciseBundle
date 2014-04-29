<?php

namespace SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\GroupItems;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\Model;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\ObjectBlock;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\Common\CommonModelFactory;

/**
 * This class manages the creation of instances of group items Model.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ModelFactory extends CommonModelFactory
{
    /**
     * Check the number of objects in each block
     *
     * @param Model $model
     *
     * @return boolean true is the model is valid
     */
    public static function validateModel(Model $model)
    {
        foreach ($model->getObjectBlocks() as $block) {
            /** @var ObjectBlock $block */
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
