<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;

/**
 * Class ExerciseController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseController extends AppController
{
    /**
     * View an exercise
     *
     * @param $exerciseId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($exerciseId)
    {
        $exercise = $this->get('simple_it.claire.exercise.exercise')->get(
            $exerciseId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:view.html.twig',
            array('exerciseModel' => $exerciseModel)
        );
    }

    /**
     * List all the exercise models
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $exerciseModels = $this->get('simple_it.claire.exercise.exercise_model')->getListByType();

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:list.html.twig',
            array('exerciseModels' => $exerciseModels)
        );
    }
}
