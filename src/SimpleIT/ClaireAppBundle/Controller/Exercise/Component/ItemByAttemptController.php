<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ItemByAttemptController extends AppController
{
    /**
     * View an exercise item. The answer form is shown if the exercise has not yet been answered
     * in this attempt.
     * If it has been answered, the answer and the correction are shown.
     *
     * @param int $attemptId
     * @param int $itemId
     *
     * @return Response
     */
    public function viewAction($attemptId, $itemId)
    {
        $attempt = $this->get('simple_it.claire.exercise.attempt')->get($attemptId);
        $exerciseId = $attempt->getExercise();

        $corrected = false;
        $item = $this->get('simple_it.claire.exercise.item')->getItemObjectFromAttempt(
            $attemptId,
            $itemId,
            $corrected
        );

        $exercise = $this->get('simple_it.claire.exercise.exercise')->get($exerciseId);
        $itemIds = $this->get('simple_it.claire.exercise.exercise')->getItemIds($exerciseId);
        if ($corrected === true) {
            $view = $this->selectCorrectedView($exercise->getContent());
        } else {
            $view = $this->selectNotCorrectedView($exercise->getContent());
        }

        return $this->render(
            $view,
            array(
                'exercise'  => $exercise->getContent(),
                'item'      => $item,
                'itemId'    => $itemId,
                'attemptId' => $attemptId,
                'itemIds'   => $itemIds
            )
        );
    }

    /**
     * Answer action. Post the learner's answer
     *
     * @param Request $request
     * @param int     $attemptId
     * @param int     $itemId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function answerAction(Request $request, $attemptId, $itemId)
    {
        $answers = $request->get('answers');

        $this->get('simple_it.claire.exercise.answer')->add(
            $attemptId,
            $itemId,
            $answers
        );
        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_item_by_attempt_view',
                array(
                    'attemptId' => $attemptId,
                    'itemId'    => $itemId
                )
            )
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
    private
    function selectCorrectedView(
        $exercise
    )
    {
        switch (get_class($exercise)) {
            case ExerciseResource::MULTIPLE_CHOICE_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/MultipleChoice/Component:corrected.html.twig';
                break;
            case ExerciseResource::GROUP_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/GroupItems/Component:corrected.html.twig';
                break;
            case ExerciseResource::ORDER_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/OrderItems/Component:corrected.html.twig';
                break;
            case ExerciseResource::PAIR_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/PairItems/Component:corrected.html.twig';
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
    private
    function selectNotCorrectedView(
        $exercise
    )
    {
        switch (get_class($exercise)) {
            case ExerciseResource::MULTIPLE_CHOICE_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/MultipleChoice/Component:answerForm.html.twig';
                break;
            case ExerciseResource::GROUP_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/GroupItems/Component:answerForm.html.twig';
                break;
            case ExerciseResource::ORDER_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/OrderItems/Component:answerForm.html.twig';
                break;
            case ExerciseResource::PAIR_ITEMS_CLASS:
                $view = 'SimpleITClaireAppBundle:Exercise/Exercise/PairItems/Component:answerForm.html.twig';
                break;
            default:
                throw new \LogicException('Unknown class of exercise: ' . get_class($exercise));
        }

        return $view;
    }
}
