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
