<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\OwnerExerciseModelPublicType;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OwnerExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelController extends AppMetadataController
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
                'simple_it_claire_component_exercise_exercise_try',
                array('exerciseId' => $exercise->getId())
            )
        );
    }

    /**
     * Browser
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function browserViewAction()
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel:browser.html.twig'
        );
    }

    /**
     * Edit an owner exercise model
     *
     * @param int $ownerExerciseModelId Owner owner model id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction($ownerExerciseModelId)
    {
        $ownerExerciseModel = $this->get('simple_it.claire.exercise.owner_exercise_model')->get(
            $ownerExerciseModelId
        );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel:edit.html.twig',
            array(
                'ownerExerciseModel' => $ownerExerciseModel
            )
        );
    }

    /**
     * List and search
     *
     * @param Request               $request               Request
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchListAction(
        Request $request,
        CollectionInformation $collectionInformation
    )
    {
        $metadataArray = $this->metadataToArray($collectionInformation);
        $miscArray = $this->miscToArray($collectionInformation);

        // TODO User
        $userId = 1000001;

        $ownerExerciseModels = $this->get('simple_it.claire.exercise.owner_exercise_model')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                true
            );

        $publicOwnerExerciseModels = $this->get(
            'simple_it.claire.exercise.owner_exercise_model'
        )->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                false
            );

        if ($request->isXmlHttpRequest()) {
            return new Response($this->searchListJson($ownerExerciseModels));
        }

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel/Component:searchList.html.twig',
            array(
                'ownerExerciseModels'       => $ownerExerciseModels,
                'publicOwnerExerciseModels' => $publicOwnerExerciseModels,
                'metadataArray'             => $metadataArray,
                'miscArray'                 => $miscArray,
                'type'                      => $collectionInformation->getFilter('type'),
                'collectionInformation'     => $collectionInformation,
                'privatePaginationUrl'      => $this->generateUrl(
                    'simple_it_claire_component_exercise_owner_exercise_model_private_list'
                ),
                'publicPaginationUrl'       => $this->generateUrl(
                    'simple_it_claire_component_exercise_owner_exercise_model_public_list'
                )
            )
        );
    }

    /**
     * List private exercise models
     *
     * @param Request               $request               Request
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function privateListAction(
        Request $request,
        CollectionInformation $collectionInformation
    )
    {
        return $this->listExerciseModels(
            true,
            'simple_it_claire_component_exercise_owner_exercise_model_private_list',
            $collectionInformation,
            $request->isXmlHttpRequest()
        );
    }

    /**
     * List public exercise models
     *
     * @param Request               $request               Request
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function publicListAction(
        Request $request,
        CollectionInformation $collectionInformation
    )
    {
        return $this->listExerciseModels(
            false,
            'simple_it_claire_component_owner_exercise_model_public_list',
            $collectionInformation,
            $request->isXmlHttpRequest()
        );
    }

    /**
     * List the resources with pagination
     *
     * @param boolean               $private List private or public exercise models
     * @param string                $action  The route to call in pagination
     * @param CollectionInformation $collectionInformation
     * @param boolean               $isXmlHttpRequest
     *
     * @return Response
     */
    private function listExerciseModels(
        $private,
        $action,
        $collectionInformation,
        $isXmlHttpRequest
    )
    {
        $metadataArray = $this->metadataToArray($collectionInformation);
        $miscArray = $this->miscToArray($collectionInformation);

        // TODO User
        $userId = 1000001;

        $ownerExerciseModels = $this->get('simple_it.claire.exercise.owner_exercise_model')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                $private
            );

        if ($isXmlHttpRequest) {
            return new Response($this->searchListJson($ownerExerciseModels));
        }

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel/Component:list.html.twig',
            array(
                'ownerExerciseModels'   => $ownerExerciseModels,
                'collectionInformation' => $collectionInformation,
                'paginationUrl'         => $this->generateUrl($action),
                'public'                => !$private
            )
        );
    }

    /**
     * Prepare result owner exercise model list in Json format
     *
     * @param $ownerExerciseModels
     *
     * @return string
     */
    protected function searchListJson($ownerExerciseModels)
    {
        $search = array();

        /** @var OwnerExerciseModelResource $oem */
        foreach ($ownerExerciseModels as $oem) {

            $search[] = array(
                'id'              => $oem->getId(),
                'exerciseModelId' => $oem->getExerciseModel(),
                'type'            => $oem->getType(),
                'title'           => $oem->getTitle(),
                'metadata'        => $oem->getMetadata(),
                'url'             => $this->generateUrl(
                    'simple_it_claire_component_exercise_owner_exercise_model_edit',
                    array(
                        'ownerExerciseModelId' => $oem->getId()
                    )
                )
            );
        }

        return json_encode($search);
    }

    /**
     * Edit an owner exercise model public status (GET)
     *
     * @param int $ownerExerciseModelId Resource id
     *
     * @return Response
     */
    public function editPublicViewAction($ownerExerciseModelId)
    {
        $ownerExerciseModel = $this->get('simple_it.claire.exercise.owner_exercise_model')->get(
            $ownerExerciseModelId
        );

        $form = $this->createForm(new OwnerExerciseModelPublicType(), $ownerExerciseModel);

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel/Component:editPublic.html.twig',
            array('ownerExerciseModel' => $ownerExerciseModel, 'form' => $form->createView())
        );
    }

    /**
     * Edit an owner exercise model public status (POST)
     *
     * @param Request $request              Request
     * @param int     $ownerExerciseModelId ownerExerciseModel id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function publicEditAction(Request $request, $ownerExerciseModelId)
    {
        $ownerExerciseModel = new OwnerExerciseModelResource();
        $form = $this->createForm(new OwnerExerciseModelPublicType(), $ownerExerciseModel);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'editPublic')) {
            $ownerExerciseModel = $this->get(
                'simple_it.claire.exercise.owner_exercise_model'
            )->save(
                    $ownerExerciseModelId,
                    $ownerExerciseModel
                );
        }

        return new JsonResponse(array(
            'id'     => $ownerExerciseModel->getId(),
            'public' => $ownerExerciseModel->getPublic()
        ));
    }

    /**
     * Delete an owner exercise model
     *
     * @param $ownerExerciseModelId
     *
     * @return JsonResponse
     */
    public function deleteAction($ownerExerciseModelId)
    {
        $this->get('simple_it.claire.exercise.owner_exercise_model')->delete($ownerExerciseModelId);

        return new JsonResponse(array('id'=>$ownerExerciseModelId));
    }
}
