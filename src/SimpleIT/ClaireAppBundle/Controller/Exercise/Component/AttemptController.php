<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
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

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_item_by_attempt_view',
                array('attemptId' => $attemptId, 'itemId' => $itemId)
            )
        );
    }
}
