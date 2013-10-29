<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Model\AppResponse;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\Collection\CollectionInformation;
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
        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $collectionInformation
            );

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource:searchList.html.twig',
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
     * @param string                $paginationUrl         Pagination url
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchListAction(
        Request $request,
        CollectionInformation $collectionInformation,
        $paginationUrl
    )
    {
        $ownerResources = $this->get('simple_it.claire.exercise.owner_resource')->getAll
            (
                $collectionInformation
            );

        if ($request->isXmlHttpRequest()) {
            return new Response($this->searchListJson($ownerResources));
        }

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/Resource:searchList.html.twig',
            array(
                'ownerResources'        => $ownerResources,
                'collectionInformation' => $collectionInformation,
                'paginationUrl'         => $paginationUrl
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
            switch ($or->getType())
            {
                case ResourceResource::MULTIPLE_CHOICE_QUESTION_CLASS:
                    /** @var ExerciseResource\MultipleChoiceQuestionResource $mcQuestion */
                    $mcQuestion = $or->getContent();
                    $content = $mcQuestion->getQuestion();
                    break;
                case ResourceResource::PICTURE_CLASS:
                    /** @var ExerciseResource\PictureResource $picture */
                    $picture = $or->getContent();
                    $content = $picture->getSource();
                    break;
                case ResourceResource::SEQUENCE_CLASS:
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
     * Edit owner resource
     *
     * @param Request      $request          Request
     * @param int | string $ownerResourceId Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $ownerResourceId)
    {
        $ownerResource = null;
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->get(
                $ownerResourceId
            );
        } elseif (RequestUtils::METHOD_POST == $request->getMethod() && $request->isXmlHttpRequest()
        ) {
            $ownerResource = $request->get('ownerResource');
            $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->save(
                $ownerResourceId,
                $ownerResource
            );

            return new AppResponse($ownerResource);
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/PartContent/Component:edit.html.twig',
            array(
                'courseIdentifier' => $ownerResourceId,
                'partContent'      => $ownerResource
            )
        );
    }
}
