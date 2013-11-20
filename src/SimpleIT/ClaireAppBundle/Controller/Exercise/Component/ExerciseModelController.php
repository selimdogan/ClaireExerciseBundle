<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ExerciseModelTitleType;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\ExerciseModelTypeType;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelController extends AppController
{
    /**
     * View an exercise model
     *
     * @param $exerciseModelId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($exerciseModelId)
    {
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->get(
            $exerciseModelId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:view.html.twig',
            array(
                'exerciseModel' => $exerciseModel,
                'content'       => print_r($exerciseModel->getContent(), true)
            )
        );
    }

    /**
     * Create an exercise model: select the type (GET)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createExerciseModelViewAction()
    {
        $form = $this->createForm(new ExerciseModelTypeType(), new ExerciseModelResource());

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel:create.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Create a new exercise model with default values in basic fields
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $exerciseModel = new ExerciseModelResource();
        $form = $this->createForm(new ExerciseModelTypeType(), $exerciseModel);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'appCreate')) {
            $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->addFromType(
                $exerciseModel
            );
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_exercise_exercise_model_edit',
                array('exerciseModelId' => $exerciseModel->getId())
            )
        );
    }

    /**
     * Edit an exercise model
     *
     * @param int $exerciseModelId Exercise model id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction($exerciseModelId)
    {
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->getToEdit(
            $exerciseModelId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel:edit.html.twig',
            array(
                'exerciseModel' => $exerciseModel
            )
        );
    }

    /**
     * Edit an exercise model type (GET)
     *
     * @param int $exerciseModelId Exercise model id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTypeViewAction($exerciseModelId)
    {
        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->getExerciseModelToEdit(
            $exerciseModelId
        );

        $form = $this->createForm(new ExerciseModelTypeType(), $exerciseModel);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editType.html.twig',
            array('exerciseModel' => $exerciseModel, 'form' => $form->createView())
        );
    }

    /**
     * Edit an exercise model type (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function typeEditAction(Request $request, $exerciseModelId)
    {
        $exerciseModel = new ExerciseModelResource();
        $form = $this->createForm(new ExerciseModelTypeType(), $exerciseModel);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'editType')) {
            $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->save(
                $exerciseModelId,
                $exerciseModel
            );
        }

        return new JsonResponse($exerciseModel->getType());
    }

    /**
     * Edit an exercise model title (GET)
     *
     * @param int $exerciseModelId Exercise model id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTitleViewAction($exerciseModelId)
    {
        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->getExerciseModelToEdit(
            $exerciseModelId
        );

        $form = $this->createForm(new ExerciseModelTitleType(), $exerciseModel);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editTitle.html.twig',
            array('exerciseModel' => $exerciseModel, 'form' => $form->createView())
        );
    }

    /**
     * Edit an exercise model title (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function titleEditAction(Request $request, $exerciseModelId)
    {
        $exerciseModel = new ExerciseModelResource();
        $form = $this->createForm(new ExerciseModelTitleType(), $exerciseModel);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'editTitle')) {
            $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->save(
                $exerciseModelId,
                $exerciseModel
            );
        }

        return new JsonResponse($exerciseModel->getTitle());
    }

    /**
     * Edit an exercise model content (GET)
     *
     * @param int $exerciseModelId Exercise model id
     *
     * @throws HttpException
     * @return Response
     */
    public function editContentViewAction($exerciseModelId)
    {
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->getExerciseModelToEdit(
            $exerciseModelId
        );

        $view = null;
        switch ($exerciseModel->getType()) {
            case CommonModel::MULTIPLE_CHOICE:
                $view = $this->editMultipleChoiceView($exerciseModel);
                break;
            case CommonModel::GROUP_ITEMS:
                $view = $this->editGroupItemsView($exerciseModel);
                break;
            case CommonModel::ORDER_ITEMS:
                $view = $this->editOrderItemsView($exerciseModel);
                break;
            case CommonModel::PAIR_ITEMS:
                $view = $this->editPairItemsView($exerciseModel);
                break;
            default:
                throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }

        return $view;
    }

    /**
     * View the edition component for a MC
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return Response
     */
    private function editMultipleChoiceView(ExerciseModelResource $exerciseModel)
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editMultipleChoiceContent.html.twig',
            array('exerciseModel' => $exerciseModel)
        );
    }

    /**
     * View the edition component for a Group Items
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return Response
     */
    private function editGroupItemsView(ExerciseModelResource $exerciseModel)
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editGroupItemsContent.html.twig',
            array('exerciseModel' => $exerciseModel)
        );
    }

    /**
     * View the edition component for a Order Items
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return Response
     */
    private function editOrderItemsView(ExerciseModelResource $exerciseModel)
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editOrderItemsContent.html.twig',
            array('exerciseModel' => $exerciseModel)
        );
    }

    /**
     * View the edition component for a Pair Items
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return Response
     */
    private function editPairItemsView(ExerciseModelResource $exerciseModel)
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editPairItemsContent.html.twig',
            array('exerciseModel' => $exerciseModel)
        );
    }

    /**
     * Edit a multiple choice content (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Resource
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function multipleChoiceContentEditAction(Request $request, $exerciseModelId)
    {
        $resourceData = $request->request->all();

        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->saveMultipleChoice(
            $exerciseModelId,
            $resourceData
        );

        return new JsonResponse($exerciseModel->getId());
    }

    /**
     * Edit a group items content (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Resource
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function groupItemsContentEditAction(Request $request, $exerciseModelId)
    {
        $resourceData = $request->request->all();
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->saveGroupItems(
            $exerciseModelId,
            $resourceData
        );

        return new JsonResponse($exerciseModel->getId());
    }

    /**
     * Edit a order items content (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Resource
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function orderItemsContentEditAction(Request $request, $exerciseModelId)
    {
        $resourceData = $request->request->all();
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->saveOrderItems(
            $exerciseModelId,
            $resourceData
        );

        return new JsonResponse($exerciseModel->getId());
    }

    /**
     * Edit a pair items content (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Resource
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function pairItemsContentEditAction(Request $request, $exerciseModelId)
    {
        $resourceData = $request->request->all();
        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->savePairItems(
            $exerciseModelId,
            $resourceData
        );

        return new JsonResponse($exerciseModel->getId());
    }

    /**
     * Delete an exercise model
     *
     * @param $exerciseModelId
     *
     * @return JsonResponse
     */
    public function deleteAction($exerciseModelId)
    {
        $this->get('simple_it.claire.exercise.exercise_model')->delete($exerciseModelId);

        return new JsonResponse($exerciseModelId);
    }
}
