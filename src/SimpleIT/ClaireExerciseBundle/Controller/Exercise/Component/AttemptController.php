<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptController extends AppController
{
    /**
     * View an attempt. Redirects to the first item of the attempt
     *
     * @param $attemptId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function viewAction($attemptId)
    {
        $itemId = $this->get('simple_it.claire.exercise.item')->getFirstItemId($attemptId);

        return $this->forward(
            'SimpleITClaireExerciseBundle:Exercise/Component/ItemByAttempt:view',
            array('attemptId' => $attemptId, 'itemId' => $itemId)
        );
    }
}
