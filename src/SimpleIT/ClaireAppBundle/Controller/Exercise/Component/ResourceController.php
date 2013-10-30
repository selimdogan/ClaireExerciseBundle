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
 * Class ResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceController extends AppController
{
    /**
     * Edit a resource type (GET)
     *
     * @param int $resourceId Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTypeViewAction($resourceId)
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

        return new JsonResponse($resource);
    }

    /**
     * Edit a resource content (GET)
     *
     * @param int $resourceId Resource id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editContentViewAction($resourceId)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        $view = null;
        switch ($resource->getType()) {
            case ExerciseResource\CommonResource::PICTURE:
                $view = $this->editPictureView($resource);
                break;
            case ExerciseResource\CommonResource::TEXT:
                $view = $this->editTextView($resource);
                break;
            case ExerciseResource\CommonResource::MULTIPLE_CHOICE_QUESTION:
                $view = $this->editMCQuestionView($resource);
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
     * View the edition component for a text
     *
     * @param ResourceResource $resource
     *
     * @return Response
     */
    private function editTextView(ResourceResource $resource)
    {
        $form = $this->createForm(new TextType(), $resource->getContent());

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editTextContent.html.twig',
            array('resource' => $resource, 'form' => $form->createView())
        );
    }

    /**
     * View the edition component for a MC question
     *
     * @param ResourceResource $resource
     *
     * @return Response
     */
    private function editMCQuestionView(ResourceResource $resource)
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editMCQuestionContent.html.twig',
            array('resource' => $resource)
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
        $form = $this->createForm(new PictureType(), $content);

        return $this->contentEdit($request, $form, $content, $resourceId);
    }

    /**
     * Edit a text resource content (POST)
     *
     * @param Request $request    Request
     * @param int     $resourceId Resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function textContentEditAction(Request $request, $resourceId)
    {
        $content = new ExerciseResource\TextResource();
        $form = $this->createForm(new TextType(), $content);

        return $this->contentEdit($request, $form, $content, $resourceId);
    }

    /**
     * Edit the resource content
     *
     * @param Request                         $request
     * @param Form                            $form
     * @param ExerciseResource\CommonResource $content
     * @param int                             $resourceId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function contentEdit(
        Request $request,
        Form $form,
        ExerciseResource\CommonResource $content,
        $resourceId
    )
    {
        $form->bind($request);
        $resource = new ResourceResource();
        if ($this->get('validator')->validate($form, 'editContent')) {
            $resource->setContent($content);
            $resource = $this->get('simple_it.claire.exercise.resource')->save(
                $resourceId,
                $resource
            );
        }

        return new JsonResponse($resource);
    }

    /**
     * Edit a multiple choice question resource content (POST)
     *
     * @param Request $request    Request
     * @param int     $resourceId Resource
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mcQuestionContentEditAction(Request $request, $resourceId)
    {
        $resourceData = $request->request->all();
        $resource = $this->get('simple_it.claire.exercise.resource')->saveMCQuestion(
            $resourceId,
            $resourceData
        );

        return new JsonResponse($resource);
    }
}
