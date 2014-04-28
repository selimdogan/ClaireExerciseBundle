<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RequiredResourceByResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByResourceController extends AppController
{
    /**
     * Edit the required resources (GET)
     *
     * @param int  $resourceId Resource id
     * @param bool $locked
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editRequiredResourcesViewAction($resourceId, $locked = false)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/Resource/Component:editRequiredResources.html.twig',
            array('resource' => $resource, 'locked' => $locked)
        );
    }

    /**
     * Edit a resource type (POST)
     *
     * @param Request $request    Request
     * @param int     $resourceId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function RequiredResourceEditAction(Request $request, $resourceId)
    {
        $resourceData = $request->request->all();
        $requiredResources = $this->get('simple_it.claire.exercise.resource')->saveRequiredResource(
            $resourceId,
            $resourceData
        );

        return new JsonResponse(array('requiredResources' => $requiredResources));
    }
}
