<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AttemptByTestAttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptByTestAttemptController extends AppController
{
    /**
     * View an attempt in a test-attempt.
     *
     * @param int $testAttemptId
     * @param int $position
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($testAttemptId, $position)
    {
        $attempts = $this->get('simple_it.claire.exercise.attempt')->getAllByTestAttempt(
            $testAttemptId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Attempt/Component:viewByTestAttempt.html.twig',
            array(
                'attempts'      => $attempts,
                'testAttemptId' => $testAttemptId,
                'position'      => $position
            )
        );

    }
}
