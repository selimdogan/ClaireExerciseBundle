<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;

/**
 * Class ExerciseModelController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ExerciseModelController extends AppController
{
    /**
     * View an exercise model
     *
     * @param int $exerciseModelId Exercise Model Id
     */
    public function viewAction($exerciseModelId)
    {
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->get($exerciseModelId);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:view.html.twig',
            array('exerciseModel' => $exerciseModel)
        );
    }
}
