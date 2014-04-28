<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RequiredResourceByExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByExerciseModelController extends AppController
{
    /**
     * Edit the required resources (GET)
     *
     * @param int  $exerciseModelId Resource id
     * @param bool $locked
     *
     * @return Response
     */
    public function editRequiredResourcesViewAction($exerciseModelId, $locked = false)
    {
        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->getExerciseModelToEdit(
                $exerciseModelId
            );

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editRequiredResources.html.twig',
            array('exerciseModel' => $exerciseModel, 'locked' => $locked)
        );
    }

    /**
     * Edit a resource type (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Course id
     *
     * @return Response
     */
    public function RequiredResourceEditAction(Request $request, $exerciseModelId)
    {
        $resourceData = $request->request->all();
        $requiredResources = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->saveRequiredResource(
                $exerciseModelId,
                $resourceData
            );

        return new JsonResponse(array('requiredResources' => $requiredResources));
    }
}
