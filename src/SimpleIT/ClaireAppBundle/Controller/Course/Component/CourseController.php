<?php

namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
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
    /* **************** *
     *                  *
     * ***** LIST ***** *
     *                  *
     * **************** */

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

    /* ****************** *
     *                    *
     * ***** COURSE ***** *
     *                    *
     * ****************** */

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
        $course = $this->get('simple_it.claire.course.course')->getToEdit(
            $courseId,
            $parameters
        );

        $form = $this->createFormBuilder($course)
            ->add('title', 'text')
            ->getForm();

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:edit.html.twig',
            array(
                'course' => $course,
                'form'   => $form->createView()
            )
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
        $course = new CourseResource();
        $form = $this->createFormBuilder($course)
            ->add('title', 'text')
            ->getForm();
        $form->bind($request);

        if ($form->isValid()) {
            $course = $this->get('simple_it.claire.course.course')->save(
                $courseId,
                $course,
                $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
            );

            return new JsonResponse($course->getTitle());
        } else {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST, $form->getErrors());
        }
    }

    /* ************************* *
     *                           *
     * ***** DISPLAY LEVEL ***** *
     *                           *
     * ************************* */

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

        $course = $this->get('simple_it.claire.course.course')->getToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
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
            $course = $this->get('simple_it.claire.course.course')->save(
                $courseId,
                $course,
                $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
            );
        }

        return $this->redirect(
            $this->generateUrl(
                'simple_it_claire_course_course_edit',
                array('courseId' => $course->getId())
            )
        );
    }

    /* ****************** *
     *                    *
     * ***** STATUS ***** *
     *                    *
     * ****************** */

    /**
     * Edit a course status to waiting for publication
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editStatusToWaitingForPublicationAction(Request $request, $courseId)
    {
        if (is_null($initialStatus = $request->get(CourseResource::STATUS))) {
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
        if (is_null($initialStatus = $request->get(CourseResource::STATUS))) {
            throw new HttpException(HTTP::STATUS_CODE_BAD_REQUEST);
        }
        $this->get('simple_it.claire.course.course')->changeStatus(
            $courseId,
            $initialStatus,
            CourseResource::STATUS_PUBLISHED
        );

        return new Response();
    }

    /* ************************ *
     *                          *
     * ***** INTRODUCTION ***** *
     *                          *
     * ************************ */

    /**
     * View introduction
     *
     * @param int|string $courseIdentifier Course id | slug
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
     * View introduction with a course status different of published
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function viewIntroductionToEditAction(Request $request, $courseId)
    {
        $introduction = $this->get('simple_it.claire.course.course')->getIntroductionToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $introduction)
        );
    }

    /**
     * Edit introduction (GET)
     *
     * @param Request $request  Request
     * @param int     $courseId Course id
     *
     * @return Response
     */
    public function editIntroductionViewAction(Request $request, $courseId)
    {
        $introduction = $this->get('simple_it.claire.course.course')->getIntroductionToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $introduction)
        );
    }

    /* *************** *
     *                 *
     * ***** TOC ***** *
     *                 *
     * *************** */

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
     * View a table of content with a course status different of published
     *
     * @param Request    $request            Request
     * @param int        $courseId           Course id
     * @param int|string $categoryIdentifier Category id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTocToEditAction(
        Request $request,
        $courseId,
        $categoryIdentifier
    )
    {
        $course = $this->get('simple_it.claire.course.course')->getToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        $toc = $this->get('simple_it.claire.course.course')->getTocToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        if ($course->getDisplayLevel() == CourseResource::DISPLAY_LEVEL_MEDIUM) {
            $template = 'SimpleITClaireAppBundle:Course/Course/Component:tocMediumEdit.html.twig';
        } else {
            $template = 'SimpleITClaireAppBundle:Course/Course/Component:tocBigEdit.html.twig';
        }

        return $this->render(
            $template,
            array(
                'toc'                => $toc,
                'course'             => $course,
                'categoryIdentifier' => $categoryIdentifier
            )
        );
    }

    /**
     * View a table of content with a course status different of published
     *
     * @param Request      $request      Request
     * @param int          $courseId     Course id
     * @param int          $displayLevel Display level
     * @param int | string $categoryId   Category id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewEditTocAction(
        Request $request,
        $courseId,
        $displayLevel,
        $categoryId
    )
    {
        $toc = $this->get('simple_it.claire.course.course')->getTocToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

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
                'courseIdentifier'   => $courseId,
                'categoryIdentifier' => $categoryId
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

    /* ******************** *
     *                      *
     * ***** TIMELINE ***** *
     *                      *
     * ******************** */

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
     * View timeline with a course status different of published
     *
     * @param Request    $request  Request
     * @param int        $courseId Course id
     * @param int|string $partId   Current part id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTimelineToEditAction(
        Request $request,
        $courseId,
        $partId = null
    )
    {

        $course = $this->get('simple_it.claire.course.course')->getToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        $category = $this->get('simple_it.claire.associated_content.category')->getByCourseToEdit(
            $course->getId(),
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        $toc = $this->get('simple_it.claire.course.course')->getTocToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:timelineEdit.html.twig',
            array(
                'toc'                => $toc,
                'partIdentifier'     => $partId,
                'course'             => $course,
                'categoryIdentifier' => $category->getSlug()
            )
        );
    }

    /* ********************** *
     *                        *
     * ***** PAGINATION ***** *
     *                        *
     * ********************** */

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
        $course = $this->get('simple_it.claire.course.course')->get($courseIdentifier);
        $pagination = $this->get('simple_it.claire.course.course')->getPagination(
            $courseIdentifier,
            $partIdentifier
        );

        $previousUrl = null;
        /** @var PartResource $previous */
        $previous = $pagination['previous'];
        if (!is_null($previous)) {
            if (PartResource::COURSE == $previous->getSubtype()) {
                $previousUrl = $this->generateUrl(
                    'simple_it_claire_course_course_view',
                    array(
                        'categoryIdentifier' => $categoryIdentifier,
                        'courseIdentifier'   => $previous->getSlug(),
                    )
                );
            } else {
                $previousUrl = $this->generateUrl(
                    'simple_it_claire_course_part_view',
                    array(
                        'categoryIdentifier' => $categoryIdentifier,
                        'courseIdentifier'   => $course->getSlug(),
                        'partIdentifier'     => $previous->getSlug(),
                    )
                );
            }
        }

        $nextUrl = null;
        /** @var PartResource $next */
        $next = $pagination['next'];
        if (!is_null($next)) {
            $nextUrl = $this->generateUrl(
                'simple_it_claire_course_part_view',
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseIdentifier'   => $course->getSlug(),
                    'partIdentifier'     => $next->getSlug(),
                )
            );
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewPagination.html.twig',
            array(
                'previous'    => $previous,
                'previousUrl' => $previousUrl,
                'next'        => $next,
                'nextUrl'     => $nextUrl
            )
        );
    }

    /**
     * View pagination with a course status different of published
     *
     * @param Request    $request            Request
     * @param int        $courseId           Course id
     * @param int|string $categoryIdentifier Category id | slug
     * @param int|string $partId             Part id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewPaginationToEditAction(
        Request $request,
        $courseId,
        $categoryIdentifier,
        $partId = null
    )
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        $course = $this->get('simple_it.claire.course.course')->getToEdit($courseId, $status);
        $pagination = $this->get('simple_it.claire.course.course')->getPaginationToEdit(
            $courseId,
            $status,
            $partId
        );

        $previousUrl = null;
        /** @var PartResource $previous */
        $previous = $pagination['previous'];
        if (!is_null($previous)) {
            if (PartResource::COURSE == $previous->getSubtype()) {
                $previousUrl = $this->generateUrl(
                    'simple_it_claire_course_course_view',
                    array(
                        'categoryIdentifier' => $categoryIdentifier,
                        'courseIdentifier'   => $course->getId(),
                        'status'             => $course->getStatus()
                    )
                );
            }
        } else {
            $previousUrl = $this->generateUrl(
                'simple_it_claire_course_part_view',
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseIdentifier'   => $course->getId(),
                    'partIdentifier'     => $previous->getId(),
                    'status'             => $course->getStatus()
                )
            );
        }

        $nextUrl = null;
        /** @var PartResource $next */
        $next = $pagination['next'];
        if (!is_null($next)) {
            $nextUrl = $this->generateUrl(
                'simple_it_claire_course_part_view',
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseIdentifier'   => $course->getId(),
                    'partIdentifier'     => $next->getId(),
                    'status'             => $course->getStatus()
                )
            );
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewPagination.html.twig',
            array(
                'previous'    => $previous,
                'previousUrl' => $previousUrl,
                'next'        => $next,
                'nextUrl'     => $nextUrl
            )
        );
    }

    /**
     * View pagination in edit mode
     *
     * @param Request    $request            Request
     * @param int        $courseId           Course id
     * @param int|string $categoryIdentifier Category id | slug
     * @param int|string $partId             Part id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewPaginationEditAction(
        Request $request,
        $courseId,
        $categoryIdentifier,
        $partId = null
    )
    {
        $status = $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT);
        $course = $this->get('simple_it.claire.course.course')->getToEdit($courseId, $status);
        $pagination = $this->get('simple_it.claire.course.course')->getPaginationToEdit(
            $courseId,
            $status,
            $partId
        );

        $previousUrl = null;
        /** @var PartResource $previous */
        $previous = $pagination['previous'];
        if (!is_null($previous)) {
            if (PartResource::COURSE == $previous->getSubtype()) {
                $previousUrl = $this->generateUrl(
                    'simple_it_claire_course_course_edit',
                    array(
                        'categoryIdentifier' => $categoryIdentifier,
                        'courseId'           => $course->getId(),
                        'status'             => $course->getStatus()
                    )
                );
            }
        } else {
            $previousUrl = $this->generateUrl(
                'simple_it_claire_course_part_edit',
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseIdentifier'   => $course->getId(),
                    'partIdentifier'     => $previous->getId(),
                    'status'             => $course->getStatus()
                )
            );
        }

        $nextUrl = null;
        /** @var PartResource $next */
        $next = $pagination['next'];
        if (!is_null($next)) {
            $nextUrl = $this->generateUrl(
                'simple_it_claire_course_part_edit',
                array(
                    'categoryIdentifier' => $categoryIdentifier,
                    'courseId'           => $course->getId(),
                    'partId'             => $next->getId(),
                    'status'             => $course->getStatus()
                )
            );
        }

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewPagination.html.twig',
            array(
                'previous'    => $previous,
                'previousUrl' => $previousUrl,
                'next'        => $next,
                'nextUrl'     => $nextUrl
            )
        );
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

        $course = $this->get('simple_it.claire.course.course')->getToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT)
        );

        $metadatas = $this->get('simple_it.claire.course.metadata')->getAllFromCourseToEdit(
            $course->getId(),
            $status,
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
                'tags'      => $tags
            )
        );
    }
}
