<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;

/**
 * Class ExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelController extends AppController
{
    /**
     * View an exercise model
     *
     * @param $exerciseModelId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($exerciseModelId)
    {
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->get(
            $exerciseModelId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:view.html.twig',
            array(
                'exerciseModel' => $exerciseModel,
            'content' => print_r($exerciseModel->getContent(), true))
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
