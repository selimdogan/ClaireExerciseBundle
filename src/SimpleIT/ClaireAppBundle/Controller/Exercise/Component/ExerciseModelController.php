<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Exception\InvalidModelException;
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
     * List exercise models (TODO dev)
     *
     * @return Response
     */
    public function listAction()
    {
        $exerciseModels = $this->get('simple_it.claire.exercise.exercise_model')->getAll();

        return $this->render(
            '@SimpleITClaireApp/Exercise/ExerciseModel/Component/list.html.twig',
            array("exerciseModels" => $exerciseModels)
        );
    }

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
     * Edit an exercise model content (GET | POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Resource
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function contentEditAction(Request $request, $exerciseModelId)
    {
        if ($request->getMethod() === "GET") {
            $exerciseModel = $this->get(
                'simple_it.claire.exercise.exercise_model'
            )->getExerciseModelToEdit(
                    $exerciseModelId
                );

            return $this->viewContentEdit($exerciseModel);
        }

        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->createExerciseModel($request->request->all());

        try {
            $this->get('simple_it.claire.exercise.exercise_model')->validateExerciseModel(
                $exerciseModel
            );
        } catch (InvalidModelException $ime) {
            $exerciseModel->setId($exerciseModelId);

            return $this->viewContentEdit($exerciseModel, array($ime->getMessage()));
        }

        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->saveExerciseModel($exerciseModelId, $exerciseModel);

        return new JsonResponse($exerciseModel->getId());
    }

    /**
     * Render the edition view for exercise model content
     *
     * @param ExerciseModelResource $exerciseModel
     * @param array                 $errors
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return Response
     */
    private function viewContentEdit(ExerciseModelResource $exerciseModel, $errors = array())
    {
        $view = null;
        switch ($exerciseModel->getType()) {
            case CommonModel::MULTIPLE_CHOICE:
                $view = 'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editMultipleChoiceContent.html.twig';
                break;
            case CommonModel::GROUP_ITEMS:
                $view = 'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editGroupItemsContent.html.twig';
                break;
            case CommonModel::ORDER_ITEMS:
                $view = 'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editOrderItemsContent.html.twig';
                break;
            case CommonModel::PAIR_ITEMS:
                $view = 'SimpleITClaireAppBundle:Exercise/ExerciseModel/Component:editPairItemsContent.html.twig';
                break;
            default:
                throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }

        return $this->render($view, array('exerciseModel' => $exerciseModel, 'errors' => $errors));
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
