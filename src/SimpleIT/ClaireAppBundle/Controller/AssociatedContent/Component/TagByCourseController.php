<?php

namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagByCourseController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByCourseController extends AppController
{
    /**
     * Get a list of tags of a course
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param mixed                 $courseIdentifier      Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache
     */
    public function listAction(CollectionInformation $collectionInformation, $courseIdentifier)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourse(
            $courseIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:viewByCourse.html.twig',
            array('tags' => $tags)
        );
    }

    /**
     * Get a list of tags of a course with status different of published
     *
     * @param Request               $request               Request
     * @param CollectionInformation $collectionInformation Collection information
     * @param int                   $courseId              Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache
     */
    public function listByStatusAction(
        Request $request,
        CollectionInformation $collectionInformation,
        $courseId
    )
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourseToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT),
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:viewByCourse.html.twig',
            array('tags' => $tags)
        );
    }

    /**
     * Edit a list of tags (GET)
     *
     * @param CollectionInformation $collectionInformation Collection Information
     * @param int                   $courseId              Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listEditViewAction(CollectionInformation $collectionInformation, $courseId)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourse(
            $courseId,
            $collectionInformation
        );
        $outputTags = array();

        /** @type TagResource $tag */
        foreach ($tags as $tag) {
            $outputTags[$tag->getId()] = $tag->getName();
        }
        $outputTags = array(1 => 'test');
        $formData = array('tags' => json_encode($outputTags));
        $form = $this->createFormBuilder($formData)
            ->add(
                'tags',
                'text',
                array(
                    'required' => true
                )
            )
            ->getForm();

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:editListByCourse.html.twig',
            array(
                'searchAction' => $this->generateUrl(
                        'simple_it_claire_associated_content_search_tag_by_course',
                        array('courseId' => $courseId)
                    ),
                'courseId'     => $courseId,
                'form'         => $form->createView()
            )
        );
    }

    /**
     * @param Request $request
     * @param         $courseId
     *
     * @return JsonResponse
     */
    public function searchListToAddAction(Request $request)
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        $course = $this->get('simple_it.claire.course.course')->getByStatus(
            $request->get('courseId'),
            $status
        );
        $courseTags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourseToEdit(
            $course->getId(),
            $status,
            new CollectionInformation()
        );

        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCategory(
            $course->getCategory()->getId()
        );

        $outputCourseTags = array();
        /** @var TagResource $tag */
        foreach ($courseTags as $tag) {
            $outputCourseTags[$tag->getId()] = $tag->getName();
        }
        $outputTags = array();
        /** @var TagResource $tag */
        foreach ($tags as $tag) {
            $outputTags[$tag->getId()] = $tag->getName();
        }
        $eligibleTags = array_diff($outputTags, $outputCourseTags);

        return new JsonResponse($eligibleTags);
    }

    /**
     * Edit tags
     *
     * @param Request         $request          Request
     * @param integer |string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editListAction(Request $request, $courseIdentifier)
    {
        $formData = array();
        $form = $this->createFormBuilder($formData);
//        $this->get('simple_it.claire.associated_content.repository.tag_by_category')->findAll();
//        foreach ($tags as $tag) {
//            if ($tagsString != '') {
//                $tagsString .= ',';
//            }
//
//            $tagsString .= $tag->getName();
//        }
//
//        return $this->render(
//            'editListByCourse.html.twig',
//            array(
//                'courseIdentifier' => $courseIdentifier,
//                'tags'             => $tagsString
//            )
//        );
    }
}
