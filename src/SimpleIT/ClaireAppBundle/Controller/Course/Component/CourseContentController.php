<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseFactory;
use SimpleIT\ClaireAppBundle\Responders\Course\Content\GetContentResponse;
use SimpleIT\ClaireAppBundle\Responders\Course\Content\SaveContentResponse;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\GetDraftContentRequestDTO;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\GetPublishedContentRequestDTO;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\GetWaitingForPublicationContentRequestDTO;
use SimpleIT\ClaireAppBundle\UseCases\Course\Content\DTO\SaveContentRequestDTO;
use SimpleIT\ClaireAppBundle\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CourseContentController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseContentController extends AppController
{
    /**
     * View content
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewAction(Request $request, $courseIdentifier)
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_PUBLISHED);

        /** @var UseCaseFactory $useCaseFactory */
        $useCaseFactory = $this->get('simple_it.claire.use_cases.use_case_factory');

        switch ($status) {
            case CourseResource::STATUS_WAITING_FOR_PUBLICATION:
                $getContentResponse = $useCaseFactory
                    ->make('GetWaitingForPublicationContent')
                    ->execute(new GetWaitingForPublicationContentRequestDTO($courseIdentifier));
                break;
            case CourseResource::STATUS_DRAFT:
                $getContentResponse = $useCaseFactory
                    ->make('GetDraftContent')
                    ->execute(new GetDraftContentRequestDTO($courseIdentifier));
                break;
            default :
                $getContentResponse = $useCaseFactory
                    ->make('GetPublishedContent')
                    ->execute(new GetPublishedContentRequestDTO($courseIdentifier));
                break;
        }

        /** @var GetContentResponse $getContentResponse */

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $getContentResponse->getContent())
        );
    }

    /**
     * View content with status different of published
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewByStatusAction(Request $request, $courseId)
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        $content = $this->get('simple_it.claire.course.course')->getContentToEdit(
            $courseId,
            $status
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $content)
        );
    }

    /**
     * Edit course content (GET)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction(Request $request, $courseId)
    {
        /** @var GetContentResponse $ucResponse */
        $ucResponse = $this->get('simple_it.claire.use_cases.use_case_factory')
            ->make('GetDraftContent')
            ->execute(new GetDraftCourseRequestDTO($courseId));

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editContent.html.twig',
            array(
                'content' => $ucResponse->getContent(),
                'action'  =>
                    $this->generateUrl(
                        'simple_it_claire_component_course_course_content_edit',
                        array('courseId' => $courseId)
                    )
            )
        );
    }

    /**
     * Edit course content (POST)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return Response
     */
    public function editAction(Request $request, $courseId)
    {
        /** @var SaveContentResponse $ucResponse */
        $ucResponse = $this->get('simple_it.claire.use_cases.use_case_factory')
            ->make('SaveContent')
            ->execute(new SaveContentRequestDTO($courseId, $content = $request->get('content')));

        return new Response($ucResponse->getContent());
    }
}
