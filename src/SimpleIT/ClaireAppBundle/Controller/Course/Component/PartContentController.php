<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PartContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartContentController extends AppController
{
//    /**
//     * View Part content
//     *
//     * @param int|string $courseIdentifier Course id | slug
//     * @param int|string $partIdentifier   Part id | slug
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
//     */
//    public function viewAction($courseIdentifier, $partIdentifier)
//    {
//        return new Response($this->get('simple_it.claire.course.part')->getContent(
//            $courseIdentifier,
//            $partIdentifier
//        ));
//    }
//
//    /**
//     * View Part content with status different of published
//     *
//     * @param Request      $request  Request
//     * @param int | string $courseId Course id
//     * @param int | string $partId   Part id
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function viewByStatusAction(Request $request, $courseId, $partId)
//    {
//        return new Response($this->get('simple_it.claire.course.part')->getContentByStatus(
//            $courseId,
//            $partId,
//            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
//        ));
//    }
//
//    /**
//     * Edit course content (GET)
//     *
//     * @param Request $request  Request
//     * @param int     $courseId Course id
//     * @param int     $partId   Part id
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function editViewAction(Request $request, $courseId, $partId)
//    {
//        $partContent = $this->get('simple_it.claire.course.part')->getContentByStatus(
//            $courseId,
//            $partId,
//            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
//        );
//
//        return $this->render(
//            'SimpleITClaireAppBundle:Course/Common/partial:editContent.html.twig',
//            array(
//                'content' => $partContent,
//                'action'  =>
//                    $this->generateUrl(
//                        'simple_it_claire_component_course_part_content_edit',
//                        array('courseId' => $courseId, 'partId' => $partId)
//                    )
//            )
//        );
//    }
//
//    /**
//     * Edit course content (POST)
//     *
//     * @param Request $request  Request
//     * @param int     $courseId Course id
//     * @param int     $partId   Part id
//     *
//     * @return Response
//     */
//    public function editAction(Request $request, $courseId, $partId)
//    {
//        $content = $request->get('content');
//        $content = $this->get('simple_it.claire.course.part')->saveContent(
//            $courseId,
//            $partId,
//            $content,
//            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
//        );
//
//        return new Response($content);
//    }
}
