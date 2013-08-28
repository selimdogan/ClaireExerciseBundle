<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\Common\CommonExercise;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Services\Exercise\ExerciseService;

/**
 * Class ExerciseController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseController extends AppController
{
    /**
     * View an exercise item. The answer form is shown if the exercise has not yet been answered.
     * If it has been answered, the answer and the correction are shown.
     *
     * @param int $exerciseId
     * @param int $itemNumber
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($exerciseId, $itemNumber = 1)
    {
        $corrected = false;

        $item = $this->get('simple_it.claire.exercise.item')->getItemObjectFromExerciseAndItem(
            $exerciseId,
            $itemNumber,
            $corrected
        );

$exercise = $this->get('simple_it.claire.exercise.exercise')->getExerciseObjectFromExercise(
            $exerciseId
        );

        if ($corrected === true) {
            $view = $this->selectCorrectedView($exercise);
        } else {
            $view = $this->selectNotCorrectedView($exercise);
        }

        return $this->render(
            $view,
            array(
                'exercise' => $exercise,
                'item'     => $item,
                'itemNumber' => $itemNumber,
                'exerciseId' => $exerciseId
            )
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

    /**
     * Get the corrected view corresponding to the type of exercise.
     *
     * @param CommonExercise $exercise
     *
     * @return string
     * @throws \LogicException
     */
    private function selectCorrectedView($exercise)
    {
        switch (get_class($exercise)) {
            case ExerciseService::MULTIPLE_CHOICE_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/MultipleChoice/Component:corrected.html.twig';
                break;
            case ExerciseService::GROUP_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/GroupItems/Component:corrected.html.twig';
                break;
            case ExerciseService::ORDER_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/OrderItems/Component:corrected.html.twig';
                break;
            case ExerciseService::PAIR_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/PairItems/Component:corrected.html.twig';
                break;
            default:
                throw new \LogicException('Unknown class of exercise: ' . get_class($exercise));
        }

        return $view;
    }

    /**
     * Get the not corrected view corresponding to the type of exercise.
     *
     * @param CommonExercise $exercise
     *
     * @return string
     * @throws \LogicException
     */
    private function selectNotCorrectedView($exercise)
    {
        switch (get_class($exercise)) {
            case ExerciseService::MULTIPLE_CHOICE_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/MultipleChoice/Component:answerForm.html.twig';
                break;
            case ExerciseService::GROUP_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/GroupItems/Component:answerForm.html.twig';
                break;
            case ExerciseService::ORDER_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/OrderItems/Component:answerForm.html.twig';
                break;
            case ExerciseService::PAIR_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/PairItems/Component:answerForm.html.twig';
                break;
            default:
                throw new \LogicException('Unknown class of exercise: ' . get_class($exercise));
        }

        return $view;
    }
}
