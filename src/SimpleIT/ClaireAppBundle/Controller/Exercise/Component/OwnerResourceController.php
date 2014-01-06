<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\ClaireAppBundle\Form\Type\Exercise\OwnerResourcePublicType;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceController extends AppController
{
    /**
     * Browser
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function browserViewAction()
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerResource:browser.html.twig'
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
            'SimpleITClaireAppBundle:Exercise/OwnerResource:edit.html.twig',
            array(
                'ownerResource' => $ownerResource
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

        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                true
            );

        // FIXME pagination
//        $ownerResources->setItemsPerPage(50);
//        $ownerResources->setPageNumber(0);
$publicOwnerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray,
                $miscArray,
                $collectionInformation,
                $userId,
                false
            );

        if ($request->isXmlHttpRequest()) {
            return new Response($this->searchListJson($ownerResources));
        }

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerResource/Component:searchList.html.twig',
            array(
                'ownerResources'        => $ownerResources,
                'publicOwnerResources'  => $publicOwnerResources,
                'metadataArray'         => $metadataArray,
                'miscArray'             => $miscArray,
                'type'                  => $collectionInformation->getFilter('type'),
                'collectionInformation' => $collectionInformation,
                'paginationUrl'         => $this->generateUrl('simple_it_claire_component_owner_resource_search_list')
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
                    'simple_it_claire_exercise_owner_resource_edit',
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
            'SimpleITClaireAppBundle:Exercise/OwnerResource/Component:editPublic.html.twig',
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

        return new JsonResponse($ownerResource->getPublic());
    }

    /**
     * Add the same metadata key/value to several ownerResources (GET)
     *
     * @return Response
     */
    public function createMultipleMetadataViewAction()
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerResource/Component:createMultipleOwnerResourcesMetadata.html.twig'
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
        $misc = $request->request->get('misc');

        if (empty($ownerResourceIds) || (empty($metaValue) && empty($misc))) {
            throw new \Exception('A value and at least one resource must be specified');
        }

        $this->get('simple_it.claire.exercise.owner_resource')->addMultipleMetadata(
            $ownerResourceIds,
            $metaKey,
            $metaValue,
            $misc
        );

        return new JsonResponse($ownerResourceIds);
    }

    /**
     * Add the same metadata key to several ownerResources values (GET)
     *
     * @return Response
     */
    public function createMultipleKeyMetadataViewAction()
    {
        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerResource/Component:createMultipleOwnerResourcesKeyMetadata.html.twig'
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

        return new JsonResponse($metaKey);
    }
}
