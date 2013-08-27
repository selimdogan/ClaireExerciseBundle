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
    /**
     * Generate an exercise
     *
     * @param int $exerciseModelId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generateAction($exerciseModelId)
    {
        $exercise = $this->get('simple_it.claire.exercise.exercise')
            ->generate($exerciseModelId);

        return $this->render(
            '@SimpleITClaireApp/Exercise/ExerciseModel/Component/validateCreation.html.twig',
            array('exerciseId' => $exercise->getId())
        );
    }
}
