<?php
/*
 * This file is part of CLAIRE.
 *
 * CLAIRE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CLAIRE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CLAIRE. If not, see <http://www.gnu.org/licenses/>
 */

namespace SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\OrderItems;

use SimpleIT\ClaireExerciseBundle\Model\ExerciseCreation\Common\CommonModelFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\Model;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\ObjectBlock;

/**
 * This class manages the creation of instances of order items Model.
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
        if ($model->isObjectList()) {
            foreach ($model->getObjectBlocks() as $block) {
                /** @var ObjectBlock $block */
                if (
                    $block->isList() &&
                    $block->getNumberOfOccurrences() > count($block->getResources())
                ) {
                    return false;
                }
            }
        }

        return true;
    }
}
