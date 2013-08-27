<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;

/**
 * Class ExerciseByExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseByExerciseModelController extends AppController
{
    public function generateAction($exerciseModelId)
    {
        $exercise = $this->get('simple_it.claire.exercise.exercise')->generate($exerciseModelId);

        return $this->redirect();
    }
}
