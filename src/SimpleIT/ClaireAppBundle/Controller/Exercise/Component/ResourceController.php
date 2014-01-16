<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
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
     * Create a resource: select the type (GET)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createResourceViewAction()
    {
        $form = $this->createForm(new ResourceTypeType(), new ResourceResource());

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource:create.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Create a new resource with default values in basic fields
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $resource = new ResourceResource();
        $form = $this->createForm(new ResourceTypeType(), $resource);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'appCreate')) {
            $resource = $this->get('simple_it.claire.exercise.resource')->addFromType($resource);
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_resource_edit',
                array('resourceId' => $resource->getId())
            )
        );
    }

    /**
     * Edit a resource
     *
     * @param int $resourceId Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction($resourceId)
    {
        // FIXME user
        $user = 1000001;

        $resource = $this->get('simple_it.claire.exercise.resource')->get(
            $resourceId
        );

        $locked = $this->isLocked($resource, $user);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource:edit.html.twig',
            array(
                'resource' => $resource,
                'locked'   => $locked
            )
        );
    }

    /**
     * Check is a resource is locked (used by others) or not
     *
     * @param ResourceResource $resource
     * @param int              $user
     *
     * @return bool
     */
    private function isLocked(ResourceResource $resource, $user)
    {
        $locked = false;
        if ($resource->getAuthor() !== $user) {
            $locked = true;
        }

        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')
            ->getByResource($resource->getId());

        /** @var OwnerResourceResource $or */
        foreach ($ownerResources as $or) {
            if ($or->getOwner() !== $user) {
                $locked = true;
            }
        }

        return $locked;
    }

    /**
     * Edit a resource type (GET)
     *
     * @param int  $resourceId Resource id
     * @param bool $locked
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTypeViewAction($resourceId, $locked = false)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        $form = $this->createForm(new ResourceTypeType(), $resource);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editType.html.twig',
            array('resource' => $resource, 'form' => $form->createView(), 'locked' => $locked)
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

        return new JsonResponse(array(
            'id'   => $resource->getId(),
            'type' => $resource->getType()
        ));
    }

    /**
     * Edit a resource content (GET)
     *
     * @param int  $resourceId Resource id
     * @param bool $locked
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editContentViewAction($resourceId, $locked = false)
    {
        $resource = $this->get('simple_it.claire.exercise.resource')->getResourceToEdit(
            $resourceId
        );

        $view = null;
        switch ($resource->getType()) {
            case ExerciseResource\CommonResource::PICTURE:
                $view = $this->editPictureView($resource, $locked);
                break;
            case ExerciseResource\CommonResource::TEXT:
                $view = $this->editTextView($resource, $locked);
                break;
            case ExerciseResource\CommonResource::MULTIPLE_CHOICE_QUESTION:
                $view = $this->editMCQuestionView($resource, $locked);
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
     * @param bool             $locked
     *
     * @return Response
     */
    private function editPictureView(ResourceResource $resource, $locked)
    {
        $form = $this->createForm(new PictureType(), $resource->getContent());

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editPictureContent.html.twig',
            array('resource' => $resource, 'form' => $form->createView(), 'locked' => $locked)
        );
    }

    /**
     * View the edition component for a text
     *
     * @param ResourceResource $resource
     * @param bool             $locked
     *
     * @return Response
     */
    private function editTextView(ResourceResource $resource, $locked)
    {
        $form = $this->createForm(new TextType(), $resource->getContent());

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editTextContent.html.twig',
            array('resource' => $resource, 'form' => $form->createView(), 'locked' => $locked)
        );
    }

    /**
     * View the edition component for a MC question
     *
     * @param ResourceResource $resource
     * @param bool             $locked
     *
     * @return Response
     */
    private function editMCQuestionView(ResourceResource $resource, $locked)
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource/Component:editMCQuestionContent.html.twig',
            array('resource' => $resource, 'locked' => $locked)
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

        return new JsonResponse(array('id' => $resource->getId()));
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

        return new JsonResponse(array('id' => $resource->getId()));
    }

    /**
     * Delete a resource
     *
     * @param $resourceId
     *
     * @return JsonResponse
     */
    public function deleteAction($resourceId)
    {
        $this->get('simple_it.claire.exercise.resource')->delete($resourceId);

        return new JsonResponse(array('id' => $resourceId));
    }

    /**
     * Duplicate a resource in a new owner resource
     *
     * @param $resourceId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction($resourceId)
    {
        $ownerResource = $this->get('simple_it.claire.exercise.resource')->duplicate(
            $resourceId
        );

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_resource_edit',
                array('resourceId' => $ownerResource->getResource())
            )
        );
    }
}
