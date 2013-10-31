<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ResourceContent\PictureType;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ResourceContent\TextType;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ResourceTypeType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param int $resourceId Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editRequiredResourcesViewAction($resourceId)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editRequiredResources.html.twig',
            array('resource' => $resource)
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

        return new JsonResponse($requiredResources);
    }
}
