<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\ClaireExerciseBundle\Exception\InvalidModelException;
use SimpleIT\ClaireExerciseBundle\Form\Type\Exercise\ExerciseModelDraftType;
use SimpleIT\ClaireExerciseBundle\Form\Type\Exercise\ExerciseModelTitleType;
use SimpleIT\ClaireExerciseBundle\Form\Type\Exercise\ExerciseModelTypeType;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\ViewModelAssembler\ExerciseModel\ContentVMAssembler;
use SimpleIT\Utils\HTTP;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class ExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelController extends Controller
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
            '@SimpleITClaireExercise/Exercise/ExerciseModel/Component/list.html.twig',
            array("exerciseModels" => $exerciseModels)
        );
    }

    /**
     * Try to answer an exercise model (look for the owner exercise model)
     *
     * @param $exerciseModelId
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function tryAction($exerciseModelId)
    {
        $ownerExerciseModels = $this->get('simple_it.claire.exercise.owner_exercise_model')
            ->getByExerciseModel($exerciseModelId);

        if (count($ownerExerciseModels) == 0) {
            throw new ResourceNotFoundException('No owner exercise model for this model.');
        }

        /** @var OwnerExerciseModelResource $ownerExerciseModel */
        $ownerExerciseModel = $ownerExerciseModels->get(0);

        return $this->forward(
            'SimpleITClaireExerciseBundle:Exercise/Component/OwnerExerciseModel:try',
            array('ownerExerciseModelId' => $ownerExerciseModel->getId())
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
            'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:view.html.twig',
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
            'SimpleITClaireExerciseBundle:Exercise/ExerciseModel:create.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Create a new exercise model with default values in basic fields
     *
     * @param Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $exerciseModel = new ExerciseModelResource();
        $form = $this->createForm(new ExerciseModelTypeType(), $exerciseModel);
        $form->bind($request);

        if (!$this->get('validator')->validate($form, 'appCreate')) {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }

        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->addFromType(
            $exerciseModel
        );

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_exercise_model_edit',
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
        // FIXME user
        $user = 1000001;

        $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->getToEdit(
            $exerciseModelId
        );

        $locked = $this->isLocked($exerciseModel, $user);

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/ExerciseModel:edit.html.twig',
            array(
                'exerciseModel' => $exerciseModel,
                'locked'        => $locked
            )
        );
    }

    /**
     * Check if an owner exercise model is locked (used by others) or not
     *
     * @param ExerciseModelResource $exerciseModel
     * @param int                   $user
     *
     * @return bool
     */
    private function isLocked(ExerciseModelResource $exerciseModel, $user)
    {
        $locked = false;

        if ($exerciseModel->getAuthor() !== $user) {
            $locked = true;
        }

        $ownerExerciseModels = $this->get('simple_it.claire.exercise.owner_exercise_model')
            ->getByExerciseModel($exerciseModel->getId());

        /** @var OwnerExerciseModelResource $oem */
        foreach ($ownerExerciseModels as $oem) {
            if ($oem->getOwner() !== $user) {
                $locked = true;
            }
        }

        return $locked;
    }

    /**
     * Edit an exercise model title (GET)
     *
     * @param int  $exerciseModelId Exercise model id
     * @param bool $locked
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTitleViewAction($exerciseModelId, $locked = false)
    {
        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->getExerciseModelToEdit(
                $exerciseModelId
            );

        $form = $this->createForm(new ExerciseModelTitleType(), $exerciseModel);

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editTitle.html.twig',
            array(
                'exerciseModel' => $exerciseModel,
                'form'          => $form->createView(),
                'locked'        => $locked
            )
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

        return new JsonResponse(array(
            'id'    => $exerciseModel->getId(),
            'title' => $exerciseModel->getTitle()
        ));
    }

    /**
     * Edit the draft status (GET)
     *
     * @param int  $exerciseModelId Exercise model id
     * @param bool $locked
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDraftViewAction($exerciseModelId, $locked = false)
    {
        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->getExerciseModelToEdit(
                $exerciseModelId
            );

        $form = $this->createForm(new ExerciseModelDraftType(), $exerciseModel);

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editDraft.html.twig',
            array(
                'exerciseModel' => $exerciseModel,
                'form'          => $form->createView(),
                'locked'        => $locked
            )
        );
    }

    /**
     * Edit the draft status (POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function draftEditAction(Request $request, $exerciseModelId)
    {
        $exerciseModel = new ExerciseModelResource();
        $form = $this->createForm(new ExerciseModelDraftType(), $exerciseModel);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'editDraft')) {
            $exerciseModel = $this->get('simple_it.claire.exercise.exercise_model')->save(
                $exerciseModelId,
                $exerciseModel
            );
        }

        return new JsonResponse(array(
            'id'    => $exerciseModel->getId(),
            'draft' => $exerciseModel->getDraft()
        ));
    }

    /**
     * Edit an exercise model content (GET | POST)
     *
     * @param Request $request         Request
     * @param int     $exerciseModelId Resource
     * @param   bool  $locked
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function contentEditAction(Request $request, $exerciseModelId, $locked = false)
    {
        if ($request->getMethod() === "GET") {
            $exerciseModel = $this->get(
                'simple_it.claire.exercise.exercise_model'
            )->getExerciseModelToEdit(
                    $exerciseModelId
                );

            return $this->viewContentEdit($exerciseModel, $locked);
        }

        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->createExerciseModel($request->request->all());

        try {
            $this->get('simple_it.claire.exercise.exercise_model')->validateExerciseModel(
                $exerciseModel
            );
        } catch (InvalidModelException $ime) {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $ime->getMessage());
        }

        $exerciseModel = $this->get(
            'simple_it.claire.exercise.exercise_model'
        )->save($exerciseModelId, $exerciseModel);

        return new JsonResponse(array(
            'id'       => $exerciseModel->getId(),
            'complete' => $exerciseModel->getComplete()
        ));
    }

    /**
     * Render the edition view for exercise model content
     *
     * @param ExerciseModelResource $exerciseModel
     * @param bool                  $locked
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return Response
     */
    private function viewContentEdit(ExerciseModelResource $exerciseModel, $locked)
    {
        $view = null;
        switch ($exerciseModel->getType()) {
            case CommonModel::MULTIPLE_CHOICE:
                $view = 'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editMultipleChoiceContent.html.twig';
                break;
            case CommonModel::GROUP_ITEMS:
                $view = 'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editGroupItemsContent.html.twig';
                break;
            case CommonModel::ORDER_ITEMS:
                $view = 'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editOrderItemsContent.html.twig';
                break;
            case CommonModel::PAIR_ITEMS:
                $view = 'SimpleITClaireExerciseBundle:Exercise/ExerciseModel/Component:editPairItemsContent.html.twig';
                break;
            default:
                throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }

        $exerciseModel = ContentVMAssembler::write($exerciseModel);

        return $this->render(
            $view,
            array(
                'exerciseModel' => $exerciseModel,
                'locked'        => $locked
            )
        );
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

        return new JsonResponse(array('id' => $exerciseModelId));
    }

    /**
     * Duplicate an exercise model in a new owner exercise model
     *
     * @param $exerciseModelId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction($exerciseModelId)
    {
        $oem = $this->get('simple_it.claire.exercise.exercise_model')->duplicate($exerciseModelId);

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_component_exercise_exercise_model_edit',
                array('exerciseModelId' => $oem->getExerciseModel())
            )
        );
    }
}
