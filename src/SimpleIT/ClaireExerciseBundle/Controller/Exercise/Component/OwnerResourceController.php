<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerResourceResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\ClaireExerciseBundle\Form\Type\Exercise\OwnerResourcePublicType;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\CommonResource;

/**
 * Class ResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceController extends AppMetadataController
{
    /**
     * Browser
     *
     * @param string $_exerciseType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function browserViewAction($_exerciseType = null)
    {
        $collectionInformation = new CollectionInformation();
        if (!is_null($_exerciseType)) {
            $collectionInformation->addFilter('exercise-type', $_exerciseType);
        }

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource:browser.html.twig',
            array('collectionInformation' => $collectionInformation)
        );
    }

    /**
     * Edit an owner resource
     *
     * @param int $ownerResourceId Owner resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction($ownerResourceId)
    {
        $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->get(
            $ownerResourceId
        );

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource:edit.html.twig',
            array(
                'ownerResource' => $ownerResource
            )
        );
    }

    /**
     * List and search
     *
     * @param CollectionInformation $collectionInformation Collection Information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchListAction(
        CollectionInformation $collectionInformation
    )
    {
        $metadataArray = $this->metadataToArray($collectionInformation);
        $miscArray = $this->miscToArray($collectionInformation);
        $typeChoices = $this->getTypeChoicesFromExerciseType($collectionInformation);
        $resourceTypeArray = $this->resourceTypeToArray($collectionInformation, $typeChoices);

        // TODO User
        $userId = 1000001;

        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                true,
                $resourceTypeArray
            );

        $publicOwnerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                false,
                $resourceTypeArray
            );

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource/Component:searchList.html.twig',
            array(
                'ownerResources'        => $ownerResources,
                'publicOwnerResources'  => $publicOwnerResources,
                'metadataArray'         => $metadataArray,
                'miscArray'             => $miscArray,
                'collectionInformation' => $collectionInformation,
                'privatePaginationUrl'  => $this->generateUrl(
                    'simple_it_claire_component_exercise_owner_resource_private_list'
                ),
                'publicPaginationUrl'   => $this->generateUrl(
                    'simple_it_claire_component_exercise_owner_resource_public_list'
                ),
                'availableTypes'        => $typeChoices,
                'selectedTypes'         => $resourceTypeArray
            )
        );
    }

    /**
     * List private resources
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
        return $this->listResources(
            true,
            'simple_it_claire_component_exercise_owner_resource_private_list',
            $collectionInformation,
            $request->isXmlHttpRequest()
        );
    }

    /**
     * List public resources
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
        return $this->listResources(
            false,
            'simple_it_claire_component_owner_resource_public_list',
            $collectionInformation,
            $request->isXmlHttpRequest()
        );
    }

    /**
     * List the resources with pagination
     *
     * @param boolean               $private List private or public resources
     * @param string                $action  The route to call in pagination
     * @param CollectionInformation $collectionInformation
     * @param boolean               $isXmlHttpRequest
     *
     * @return Response
     */
    private function listResources(
        $private,
        $action,
        $collectionInformation,
        $isXmlHttpRequest
    )
    {
        $metadataArray = $this->metadataToArray($collectionInformation);
        $miscArray = $this->miscToArray($collectionInformation);
        $typeChoices = $this->getTypeChoicesFromExerciseType($collectionInformation);
        $resourceTypeArray = $this->resourceTypeToArray($collectionInformation, $typeChoices);

        // TODO User
        $userId = 1000001;

        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                $private,
                $resourceTypeArray
            );

        if ($isXmlHttpRequest) {
            return new Response($this->searchListJson($ownerResources));
        }

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource/Component:list.html.twig',
            array(
                'ownerResources'        => $ownerResources,
                'collectionInformation' => $collectionInformation,
                'paginationUrl'         => $this->generateUrl($action),
                'public'                => !$private
            )
        );
    }

    /**
     * Prepare result owner resource list in Json format
     *
     * @param $ownerResources
     *
     * @return string
     */
    protected function searchListJson($ownerResources)
    {
        $search = array();
        foreach ($ownerResources as $or) {
            /** @var OwnerResourceResource $or */
            switch ($or->getType()) {
                case ExerciseResource\CommonResource::MULTIPLE_CHOICE_QUESTION:
                    /** @var ExerciseResource\MultipleChoiceQuestionResource $mcQuestion */
                    $mcQuestion = $or->getContent();
                    $content = $mcQuestion->getQuestion();
                    break;
                case ExerciseResource\CommonResource::PICTURE:
                    /** @var ExerciseResource\PictureResource $picture */
                    $picture = $or->getContent();
                    $content = $picture->getSource();
                    break;
                case ExerciseResource\CommonResource::SEQUENCE:
                    /** @var ExerciseResource\SequenceResource $sequence */
                    $sequence = $or->getContent();
                    $content = $sequence->getSequenceType();
                    break;
                default:
                    $content = null;
            }

            $search[] = array(
                'id'         => $or->getId(),
                'resourceId' => $or->getResource(),
                'type'       => $or->getType(),
                'content'    => $content,
                'metadata'   => $or->getMetadata(),
                'url'        => $this->generateUrl(
                    'simple_it_claire_component_exercise_owner_resource_edit',
                    array(
                        'ownerResourceId' => $or->getId()
                    )
                )
            );
        }

        return json_encode($search);
    }

    /**
     * Edit an owner resource public status (GET)
     *
     * @param int $ownerResourceId Resource id
     *
     * @return Response
     */
    public function editPublicViewAction($ownerResourceId)
    {
        $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->get(
            $ownerResourceId
        );

        $form = $this->createForm(new OwnerResourcePublicType(), $ownerResource);

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource/Component:editPublic.html.twig',
            array('ownerResource' => $ownerResource, 'form' => $form->createView())
        );
    }

    /**
     * Edit an owner resource public status (POST)
     *
     * @param Request $request         Request
     * @param int     $ownerResourceId ownerResource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function publicEditAction(Request $request, $ownerResourceId)
    {
        $ownerResource = new OwnerResourceResource();
        $form = $this->createForm(new OwnerResourcePublicType(), $ownerResource);
        $form->bind($request);
        if ($this->get('validator')->validate($form, 'editPublic')) {
            $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->save(
                $ownerResourceId,
                $ownerResource
            );
        }

        return new JsonResponse(array(
            'id'     => $ownerResource->getId(),
            'public' => $ownerResource->getPublic()
        ));
    }

    /**
     * Add the same metadata key/value to several ownerResources (GET)
     *
     * @return Response
     */
    public function createMultipleMetadataViewAction()
    {
        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource/Component:createMultipleOwnerResourcesMetadata.html.twig'
        );
    }

    /**
     * Add the same metadata key/value to several ownerResources (POST)
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function multipleMetadataCreateAction(Request $request)
    {
        $ownerResourceIds = $request->request->get('resourceIds');
        $metaKey = $request->request->get('metaKey');
        $metaValue = $request->request->get('metaValue');
        $keyword = $request->request->get('keyword');

        if (empty($ownerResourceIds) || (empty($metaValue) && empty($keyword))) {
            throw new \Exception('A value and at least one resource must be specified');
        }

        $this->get('simple_it.claire.exercise.owner_resource')->addMultipleMetadata(
            $ownerResourceIds,
            $metaKey,
            $metaValue,
            $keyword
        );

        return new JsonResponse(array('ids' => $ownerResourceIds));
    }

    /**
     * Add the same metadata key to several ownerResources values (GET)
     *
     * @return Response
     */
    public function createMultipleKeyMetadataViewAction()
    {
        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource/Component:createMultipleOwnerResourcesKeyMetadata.html.twig'
        );
    }

    /**
     * Add the same metadata key to several ownerResources values  (POST)
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function multipleKeyMetadataCreateAction(Request $request)
    {
        $ownerResourceIds = $request->request->get('resourceIds');
        $values = $request->request->get('values');
        $metaKey = $request->request->get('metaKey');

        if (empty($ownerResourceIds) || empty($metaKey)) {
            throw new \Exception('A key and at least one resource must be specified');
        }

        $this->get('simple_it.claire.exercise.owner_resource')->addMultipleKeyMetadata(
            $metaKey,
            $ownerResourceIds,
            $values
        );

        return new JsonResponse(array('key' => $metaKey, 'ids' => $ownerResourceIds));
    }

    /**
     * Delete an owner resource
     *
     * @param $ownerResourceId
     *
     * @return JsonResponse
     */
    public function deleteAction($ownerResourceId)
    {
        $this->get('simple_it.claire.exercise.owner_resource')->delete($ownerResourceId);

        return new JsonResponse(array('id' => $ownerResourceId));
    }

    /**
     * Get the list of type choices for resources depending on the type of exercise that is
     * being authored
     *
     * @param CollectionInformation $collectionInformation
     *
     * @return array
     */
    private function getTypeChoicesFromExerciseType(CollectionInformation $collectionInformation)
    {
        switch ($collectionInformation->getFilter('exercise-type')) {
            case CommonModel::MULTIPLE_CHOICE:
                $choices = array(
                    'Question de QCM' => CommonResource::MULTIPLE_CHOICE_QUESTION
                );
                break;
            case CommonModel::GROUP_ITEMS:
            case CommonModel::PAIR_ITEMS:
                $choices = array(
                    'Image' => CommonResource::PICTURE,
                    'Texte' => CommonResource::TEXT
                );
                break;
            case CommonModel::ORDER_ITEMS:
                $choices = array(
                    'Image'    => CommonResource::PICTURE,
                    'Texte'    => CommonResource::TEXT,
                    'Séquence' => CommonResource::SEQUENCE
                );
                break;
            default:
                $choices = array(
                    'Image'           => CommonResource::PICTURE,
                    'Texte'           => CommonResource::TEXT,
                    'Question de QCM' => CommonResource::MULTIPLE_CHOICE_QUESTION,
                    'Séquence'        => CommonResource::SEQUENCE
                );
        }
        return $choices;
    }

    /**
     * Get the selected types in the possible types
     *
     * @param CollectionInformation $collectionInformation
     * @param array                 $typeChoices
     *
     * @return array
     */
    private function resourceTypeToArray(
        CollectionInformation $collectionInformation,
        array $typeChoices
    )
    {
        $types = array();
        foreach ($typeChoices as $type) {
            if ($collectionInformation->getFilter($type) === "1") {
                $types[] = $type;
            }
        }

        if (empty($types)) {
            return $typeChoices;
        }

        return $types;
    }
}
