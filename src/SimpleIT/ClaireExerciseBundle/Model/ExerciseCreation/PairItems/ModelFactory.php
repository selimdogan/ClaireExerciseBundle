<?php

namespace SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\PairItems;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\PairItems\Model;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\PairItems\PairBlock;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\Common\CommonModelFactory;

/**
 * This class manages the creation of instances of pair items Model.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ModelFactory extends CommonModelFactory
{
    /**
     * Check the number of pairs in each block
     *
     * @param Model $model
     *
     * @return boolean true is the model is valid
     */
    public static function validateModel(Model $model)
    {
        foreach ($model->getPairBlocks() as $block) {
            /** @var PairBlock $block */
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
