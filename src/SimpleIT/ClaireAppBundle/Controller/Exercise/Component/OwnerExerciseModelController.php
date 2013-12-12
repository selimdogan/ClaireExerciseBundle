<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OwnerExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelController extends AppController
{
    /**
     * Try to answer an owner exercise model (generate an exercise and redirect to its resolution)
     *
     * @param $ownerExerciseModelId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function tryAction($ownerExerciseModelId)
    {
        $exercise = $this->get('simple_it.claire.exercise.exercise')->generate(
            $ownerExerciseModelId
        );

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_try',
                array('exerciseId' => $exercise->getId())
            )
        );
    }
}
