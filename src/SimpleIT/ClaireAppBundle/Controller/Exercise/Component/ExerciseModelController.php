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

    public function generateAction($exerciseModelId)
    {
        $exercise = $this->get('simple_it.claire.exercise.exercise_model')->generate
            (
                $exerciseModelId
            );

        return $this->redirect();
    }
}
