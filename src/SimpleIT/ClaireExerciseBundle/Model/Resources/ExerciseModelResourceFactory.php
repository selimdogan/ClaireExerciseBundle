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

namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;

/**
 * Class ExerciseModelResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExerciseModelResourceFactory extends SharedResourceFactory
{

    /**
     * Create an ExerciseModel Resource collection
     *
     * @param array $exerciseModels
     * @param bool  $links
     *
     * @return array
     */
    public static function createCollection(array $exerciseModels, $links = false)
    {
        $exerciseModelResources = array();
        foreach ($exerciseModels as $exerciseModel) {
            $exerciseModelResources[] = self::create($exerciseModel, $links);
        }

        return $exerciseModelResources;
    }

    /**
     * Create an ExerciseModel Resource
     *
     * @param ExerciseModel $exerciseModel
     * @param bool          $links
     *
     * @return ExerciseModelResource
     */
    public static function create(ExerciseModel $exerciseModel, $links = false)
    {
        $exerciseModelResource = new ExerciseModelResource();
        parent::fill($exerciseModelResource, $exerciseModel);

        // required resources
        $rr = array();
        foreach ($exerciseModel->getRequiredExerciseResources() as $req) {
            /** @var \SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource $req */
            $rr[] = $req->getId();
        }
        $exerciseModelResource->setRequiredExerciseResources($rr);

        // required knowledges
        $rn = array();
        foreach ($exerciseModel->getRequiredKnowledges() as $req) {
            /** @var Knowledge $req */
            $rn[] = $req->getId();
        }
        $exerciseModelResource->setRequiredKnowledges($rn);

        if ($links) {
            $exercises = array();
            foreach ($exerciseModel->getExercises() as $ex) {
                $exercises[] = ExerciseResourceFactory::create($ex, true);
            }
            $exerciseModelResource->setExercises($exercises);
        }

        // removable
        if (count($exerciseModel->getExercises()) > 0) {
            $exerciseModelResource->setRemovable(false);
        } else {
            $exerciseModelResource->setRemovable(true);
        }

        return $exerciseModelResource;
    }
}
