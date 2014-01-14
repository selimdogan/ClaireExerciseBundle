<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use OC\CLAIRE\BusinessRules\Requestors\UseCaseFactory;
use OC\CLAIRE\BusinessRules\Responders\Course\Content\GetContentResponse;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use OC\CLAIRE\BusinessRules\Responders\Course\Content\SaveContentResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\GetDraftContentRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\GetPublishedContentRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\GetWaitingForPublicationContentRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Content\DTO\SaveContentRequestDTO;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetDraftCourseRequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimpleIT\AppBundle\Annotation\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
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
    public function viewPublishedAction($courseIdentifier)
    {
        /** @var UseCaseFactory $useCaseFactory */
        $useCaseFactory = $this->get('oc.claire.use_cases.course_use_case_factory');

        $getContentResponse = $useCaseFactory
            ->make('GetPublishedContent')
            ->execute(new GetPublishedContentRequestDTO($courseIdentifier));

        /** @var GetContentResponse $getContentResponse */

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $getContentResponse->getContent())
        );
    }

    public function viewAction($courseId, $status)
    {
        /** @var UseCaseFactory $useCaseFactory */
        $useCaseFactory = $this->get('oc.claire.use_cases.course_use_case_factory');

        switch ($status) {
            case CourseResource::STATUS_WAITING_FOR_PUBLICATION:
                $getContentResponse = $useCaseFactory
                    ->make('GetWaitingForPublicationContent')
                    ->execute(new GetWaitingForPublicationContentRequestDTO($courseId));
                break;
            case CourseResource::STATUS_DRAFT:
                $getContentResponse = $useCaseFactory
                    ->make('GetDraftContent')
                    ->execute(new GetDraftContentRequestDTO($courseId));
                break;
            default :
                throw new NotFoundHttpException();
        }

        /** @var GetContentResponse $getContentResponse */

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $getContentResponse->getContent())
        );
    }

    /**
     * Edit course content (GET)
     *
     * @param int $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction($courseId)
    {
        /** @var GetContentResponse $ucResponse */
        $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
            ->make('GetDraftContent')
            ->execute(new GetDraftCourseRequestDTO($courseId));

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editContent.html.twig',
            array(
                'content' => $ucResponse->getContent(),
                'action'  =>
                    $this->generateUrl(
                        'simple_it_claire_course_component_course_content_edit',
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
        $ucResponse = $this->get('oc.claire.use_cases.course_use_case_factory')
            ->make('SaveContent')
            ->execute(new SaveContentRequestDTO($courseId, $content = $request->get('content')));

        return new Response($ucResponse->getContent());
    }
}
