<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\ClaireAppBundle\Form\Type\Course\CourseDisplayLevelType;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\FormatUtils;
use SimpleIT\Utils\HTTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CourseController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseController extends AppController
{
    /**
     * List courses
     *
     * @param CollectionInformation $collectionInformation Collection Information
     * @param string                $paginationUrl         Pagination url
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $paginationUrl
    )
    {
        $courses = $this->get('simple_it.claire.course.course')->getAll($collectionInformation);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:searchList.html.twig',
            array(
                'courses'               => $courses,
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
     * @Cache
     */
    public function searchListAction(
        Request $request,
        CollectionInformation $collectionInformation,
        $paginationUrl
    )
    {
        $courses = $this->get('simple_it.claire.course.course')->getAll($collectionInformation);
        if ($request->isXmlHttpRequest()) {
            return new Response($this->get('serializer')->serialize(
                $courses->toArray(),
                FormatUtils::JSON
            ));
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:searchList.html.twig',
            array(
                'courses'               => $courses,
                'collectionInformation' => $collectionInformation,
                'paginationUrl'         => $paginationUrl
            )
        );
    }

    /**
     * View introduction
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewIntroductionAction($courseIdentifier)
    {
        $introduction = $this->get('simple_it.claire.course.course')->getIntroduction(
            $courseIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $introduction)
        );
    }

    /**
     * View timeline
     *
     * @param int | string $courseIdentifier   Course id | slug
     * @param int          $displayLevel       Display level
     * @param int | string $categoryIdentifier Category id | slug
     * @param int | string $partIdentifier     Current part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewTimelineAction(
        $courseIdentifier,
        $displayLevel,
        $categoryIdentifier = null,
        $partIdentifier = null
    )
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTimeline.html.twig',
            array(
                'toc'                => $toc,
                'displayLevel'       => $displayLevel,
                'partIdentifier'     => $partIdentifier,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier
            )
        );
    }

    /**
     * View table of content
     *
     * @param int | string $courseIdentifier   Course id | slug
     * @param int          $displayLevel       Display level
     * @param int | string $categoryIdentifier Category id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewTocAction($courseIdentifier, $displayLevel, $categoryIdentifier)
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        if ($displayLevel == CourseResource::DISPLAY_LEVEL_MEDIUM) {
            $template = 'SimpleITClaireAppBundle:Course/Course/Component:viewTocMedium.html.twig';
        } else {
            $template = 'SimpleITClaireAppBundle:Course/Course/Component:viewTocBig.html.twig';
        }

        return $this->render(
            $template,
            array(
                'toc'                => $toc,
                'displayLevel'       => $displayLevel,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier
            )
        );
    }

    /**
     * View TocAside
     *
     * @param int | string $courseIdentifier   Course id | slug
     * @param int          $displayLevel       Display level
     * @param int | string $categoryIdentifier Category id | slug
     * @param int | string $partIdentifier     Current part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewTocAsideAction(
        $courseIdentifier,
        $displayLevel,
        $categoryIdentifier,
        $partIdentifier = null
    )
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTocAside.html.twig',
            array(
                'toc'                => $toc,
                'displayLevel'       => $displayLevel,
                'partIdentifier'     => $partIdentifier,
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier
            )
        );
    }

    /**
     * View pagination
     *
     * @param int | string $courseIdentifier   Course id | slug
     * @param int | string $categoryIdentifier Category id | slug
     * @param int | string $partIdentifier     Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewPaginationAction(
        $courseIdentifier,
        $categoryIdentifier,
        $partIdentifier = null
    )
    {
        $pagination = $this->get('simple_it.claire.course.course')->getPagination(
            $courseIdentifier,
            $partIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewPagination.html.twig',
            array(
                'courseIdentifier'   => $courseIdentifier,
                'categoryIdentifier' => $categoryIdentifier,
                'previous'           => $pagination['previous'],
                'next'               => $pagination['next']
            )
        );
    }

    /**
     * View content
     *
     * @param Request      $request          Request
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewContentAction(Request $request, $courseIdentifier)
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_PUBLISHED);
        $content = $this->get('simple_it.claire.course.course')->getContent(
            $courseIdentifier,
            $status
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $content)
        );
    }

    /**
     * Create a course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction()
    {
        $course = $this->get('simple_it.claire.course.course')->add();

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_course_course_edit',
                array('courseId' => $course->getId())
            )
        );
    }

    /**
     * Edit a course
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction(Request $request, $courseId)
    {
        $parameters[CourseResource::STATUS] = $request->get(
            CourseResource::STATUS,
            CourseResource::STATUS_DRAFT
        );
        $course = $this->get('simple_it.claire.course.course')->getCourseToEdit(
            $courseId,
            $parameters
        );

        $form = $this->createFormBuilder($course)
            ->add('title', 'text')
            ->getForm();

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:edit.html.twig',
            array('course' => $course, 'form' => $form->createView())
        );
    }

    /**
     * Edit a course (POST)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, $courseId)
    {
        $parameters[CourseResource::STATUS] = $request->get(
            CourseResource::STATUS,
            CourseResource::STATUS_DRAFT
        );
        $course = new CourseResource();
        $form = $this->createFormBuilder($course)
            ->add('title', 'text')
            ->getForm();
        $form->bind($request);

        if ($form->isValid()) {
            $course = $this->get('simple_it.claire.course.course')->save(
                $courseId,
                $course,
                $parameters
            );

            return new JsonResponse($course->getTitle());
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }
    }

    /**
     * Edit a course display level (GET)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDisplayLevelViewAction(Request $request, $courseId)
    {
        $parameters[CourseResource::STATUS] = $request->get(
            CourseResource::STATUS,
            CourseResource::STATUS_DRAFT
        );

        $course = $this->get('simple_it.claire.course.course')->getCourseToEdit(
            $courseId,
            $parameters
        );

        $form = $this->createForm(new CourseDisplayLevelType(), $course);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editDisplayLevel.html.twig',
            array('course' => $course, 'form' => $form->createView())
        );
    }

    /**
     * Edit a course display level (POST)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayLevelEditAction(Request $request, $courseId)
    {
        $course = new CourseResource();
        $form = $this->createForm(new CourseDisplayLevelType(), $course);
        $form->bind($request);
        if ($form->isValid()) {
            $course = $this->get('simple_it.claire.course.course')->save($courseId, $course);
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_course_course_edit',
                array('courseId' => $course->getId())
            )
        );
    }

    /**
     * Edit a course status to waiting for publication
     *
     * @param int    $courseId      Course id
     * @param string $initialStatus Initial status
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editStatusToWaitingForPublicationAction($courseId, $initialStatus)
    {
        if (is_null($initialStatus)) {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }
        $this->get('simple_it.claire.course.course')->changeStatus(
            $courseId,
            $initialStatus,
            CourseResource::STATUS_WAITING_FOR_PUBLICATION
        );

        return new Response();
    }

    /**
     * Edit a course status to published
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editStatusToPublishedAction(Request $request, $courseId)
    {
        $initialStatus = $request->get(CourseResource::STATUS);
        if (is_null($initialStatus)) {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }
        $this->get('simple_it.claire.course.course')->changeStatus(
            $courseId,
            $initialStatus,
            CourseResource::STATUS_PUBLISHED
        );

        return new Response();
    }

    /**
     * Edit Dashboard
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDashboardAction(Request $request, $courseId)
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        $parameters[CourseResource::STATUS] = $status;
        $collectionInformation = new CollectionInformation();
        $collectionInformation->addFilter(CourseResource::STATUS, $status);

        $course = $this->get('simple_it.claire.course.course')->get(
            $courseId,
            $parameters
        );
        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromCourse(
            $course->getId(),
            $collectionInformation
        );

        $authors = $this->get('simple_it.claire.user.author')->getAllByCourse(
            $course->getId(),
            $collectionInformation
        );
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourse(
            $course->getId(),
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:editDashboard.html.twig',
            array(
                'course'    => $course,
                'metadatas' => $metadatas,
                'authors'   => $authors,
                'tags'      => $tags,
            )
        );
    }
}
