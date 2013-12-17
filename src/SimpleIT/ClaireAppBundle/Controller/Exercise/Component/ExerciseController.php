<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ExerciseController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseController extends AppController
{
    /**
     * Try a new attempt for this exercise. It creates the attempt and redirects to the exercise
     * resolution
     *
     * @param $exerciseId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function tryAction($exerciseId)
    {
        $attempt = $this->get('simple_it.claire.exercise.attempt')->create($exerciseId);

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_attempt_view',
                array('attemptId' => $attempt->getId())
            )
        );
    }
}
