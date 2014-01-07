<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
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
class OwnerExerciseModelController extends AppController
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
     * List ownerExerciseModels
     *
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $metadataArray = $this->metadataToArray($collectionInformation);
        $miscArray = $this->miscToArray($collectionInformation);

        $ownerExerciseModels = $this->get('simple_it.claire.exercise.owner_exercise_model')->getAll
            (
                $metadataArray,
                $miscArray
            );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel/Component:list.html.twig',
            array(
                'ownerExerciseModels'   => $ownerExerciseModels,
                'collectionInformation' => $collectionInformation
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
                $userId,
                true,
                $collectionInformation->getFilter('type')
            );

        $publicOwnerExerciseModels = $this->get(
            'simple_it.claire.exercise.owner_exercise_model'
        )->getAll
            (
                $metadataArray,
                $miscArray,
                $userId,
                false,
                $collectionInformation->getFilter('type')
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
                'type'                      => $collectionInformation->getFilter('type')
            )
        );
    }

    /**
     * Create an array of metadata
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return array
     */
    private function metadataToArray(CollectionInformation $collectionInformation)
    {
        $filters = $collectionInformation->getFilters();

        $metadata = array();
        foreach ($filters as $key => $filter) {
            $str = str_split($key, strlen('metaKey'));
            if ($str[0] == 'metaKey' && is_numeric($str[1])) {
                $metadata[$filter] = $filters['metaValue' . $str[1]];
            }
        }

        return $metadata;
    }

    /**
     * Create an array of keywords from collection information
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return array
     */
    private function miscToArray(CollectionInformation $collectionInformation)
    {
        $filters = $collectionInformation->getFilters();

        $misc = array();
        foreach ($filters as $key => $filter) {
            $str = str_split($key, strlen('misc'));
            if ($str[0] == 'misc' && is_numeric($str[1])) {
                $misc[] = $filter;
            }
        }

        return $misc;
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

        return new JsonResponse($ownerExerciseModel->getPublic());
    }
}
