<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AttemptController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AttemptController extends Controller
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
