<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Model\AppResponse;
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
     * List ownerResources
     *
     * @param CollectionInformation $collectionInformation Collection Information
     * @param string                $paginationUrl         Pagination url
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $paginationUrl
    )
    {
        $metadataArray = $this->metadataToArray($collectionInformation);
        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray
            );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerResource:searchList.html.twig',
            array(
                'ownerResources'        => $ownerResources,
                'collectionInformation' => $collectionInformation,
                'paginationUrl'         => $paginationUrl
            )
        );
    }

    /**
     * List courses
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

        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $metadataArray
            );

        if ($request->isXmlHttpRequest()) {
        return new Response($this->searchListJson($ownerResources));

        }

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerResource:searchList.html.twig',
            array(
                'ownerResources'        => $ownerResources,
                'collectionInformation' => $collectionInformation,
                'metadataArray'         => $metadataArray
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
     * @return \Symfony\Component\HttpFoundation\Response
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
}
