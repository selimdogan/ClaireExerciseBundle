<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ResourceContent\PictureType;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ResourceTypeType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceController extends AppController
{
    /**
     * Edit a resource type (GET)
     *
     * @param Request $request    Request
     * @param int     $resourceId Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTypeViewAction(Request $request, $resourceId)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        $form = $this->createForm(new ResourceTypeType(), $resource);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editType.html.twig',
            array('resource' => $resource, 'form' => $form->createView())
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
    public function typeEditAction(Request $request, $resourceId)
    {
        $resource = new ResourceResource();
        $form = $this->createForm(new ResourceTypeType(), $resource);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'editType')) {
            $resource = $this->get('simple_it.claire.exercise.resource')->save(
                $resourceId,
                $resource
            );
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_resource_type_edit_view',
                array('resourceId' => $resource->getId())
            )
        );
    }

    /**
     * Edit a resource content (GET)
     *
     * @param Request $request    Request
     * @param int     $resourceId Resource id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editContentViewAction(Request $request, $resourceId)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        $view = null;
        switch ($resource->getType()) {
            case ExerciseResource\CommonResource::PICTURE:
                $view = $this->editPictureView($resource);
                break;
            default:
                throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }

        return $view;
    }

    /**
     * View the edition component for a picture
     *
     * @param ResourceResource $resource
     *
     * @return Response
     */
    private function editPictureView(ResourceResource $resource)
    {
        $form = $this->createForm(new PictureType(), $resource->getContent());

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editPictureContent.html.twig',
            array('resource' => $resource, 'form' => $form->createView())
        );
    }

    /**
     * Edit a picture resource content (POST)
     *
     * @param Request $request    Request
     * @param int     $resourceId Resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pictureContentEditAction(Request $request, $resourceId)
    {
        $content = new ExerciseResource\PictureResource();
//        return print_r($content,true);
        $form = $this->createForm(new PictureType(), $content);
        $form->bind($request);
        $resource = new ResourceResource();
        if ($this->get('validator')->validate($form, 'editType')) {
            $resource->setContent($content);
            $resource = $this->get('simple_it.claire.exercise.resource')->save(
                $resourceId,
                $resource
            );
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_resource_content_edit_view',
                array('resourceId' => $resource->getId())
            )
        );
    }
}
