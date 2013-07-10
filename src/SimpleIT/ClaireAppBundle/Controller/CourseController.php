<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use SimpleIT\ClaireAppBundle\Model\Course\Part;
use SimpleIT\ClaireAppBundle\Model\Metadata;
use SimpleIT\ClaireAppBundle\Model\CourseFactory;
use SimpleIT\ClaireAppBundle\Services\CourseService;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\AppBundle\Services\ApiService;

use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\Page;
use SimpleIT\Utils\Collection\Sort;
use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Course controller
 *
 * The course controller is used to handle all the actions for courses and
 * the parts of a course
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseController extends BaseController
{
    /** @var Service The course service*/
    protected $courseService;

    /**
     * Shows a course
     *
     * @param Request $request      Request
     * @param string  $categorySlug The slug for the category
     * @param string  $courseSlug   The slug for the course
     *
     * @return Response
     */
    public function courseAction(Request $request, $categorySlug, $courseSlug)
    {
        $data = $this->processCourseAction($request, $categorySlug, $courseSlug);

        return $this->render($data['view'], $data['parameters']);
    }

    /**
     * Shows a course
     *
     * @param Request $request      Request
     * @param string  $categorySlug The slug for the category
     * @param string  $courseSlug   The slug for the course
     *
     * @return array <ul>
     *                   <li>view</li>
     *                   <li>parameters</li>
     *               </ul>
     */
    public function processCourseAction($categorySlug, $courseSlug)
    {
        $this->courseService = $this->get('simple_it.claire.course.course');

        $course = $this->courseService->getCourseWithComplementaries($courseSlug, $categorySlug);

        $displayLevel = $course->getDisplayLevel();

        $pagination = $this->courseService->getPagination($course, null, $displayLevel);

        $metadatas = $course->getMetadatas();

        $data['view'] = $this->getCourseView($displayLevel);
        $data['parameters'] =
            array('title' => $course->getTitle(),
                  'course' => $course,
                  'category' => $course->getCategory(),
                  'icon' => (isset($metadatas[ Metadata::COURSE_METADATA_IMAGE]) ? $metadatas[ Metadata::COURSE_METADATA_IMAGE] : null),
                  'aggregateRating' => (isset($metadatas[Metadata::COURSE_METADATA_AGGREGATE_RATING]) ? $metadatas[Metadata::COURSE_METADATA_AGGREGATE_RATING] : null),
                  'difficulty' => (isset($metadatas[Metadata::COURSE_METADATA_DIFFICULTY]) ? $metadatas[Metadata::COURSE_METADATA_DIFFICULTY] : null),
                  //FIXME DateInterval
                  'duration' =>(isset($metadatas[Metadata::COURSE_METADATA_DURATION]) ? $metadatas[Metadata::COURSE_METADATA_DURATION] : null),
                  'timeline' => $this->courseService->getTimeline($course),
                  'tags' => $course->getTags(),
                  'updatedAt' => $course->getUpdatedAt(),
                  'introduction' => $course->getIntroduction(),
                  'toc' => $this->courseService->getDisplayToc($course, $displayLevel),
                  'pagination' => $pagination,
                  //FIXME license
                  'license' => (isset($metadatas[Metadata::COURSE_METADATA_LICENSE]) ? $metadatas[Metadata::COURSE_METADATA_LICENSE] : null),
                  'description' => (isset($metadatas[Metadata::COURSE_METADATA_DESCRIPTION]) ? $metadatas[Metadata::COURSE_METADATA_DESCRIPTION] : null),
                  'authors' => $course->getAuthors()
            );

        if ($displayLevel == 0) {
            $data['parameters']['contentHtml'] = $this->courseService->getCourseContent($courseSlug);
            if (null != $data['parameters']['contentHtml']) {
                $data['parameters']['contentHtml'] = $this->courseService->getFormatedContent($data['parameters']['contentHtml']);
            }
        }

        return $data;
    }

    /**
     * Get the associated view
     *
     * @param int $displayLevel The display level of the course
     *
     * @return string The view template
     */
    private function getCourseView($displayLevel)
    {
        $this->courseService->checkCourseDisplayLevelValidity($displayLevel);

        if ($displayLevel == 0) {
            $view = 'TutorialBundle:Tutorial:view00.html.twig';
        } elseif ($displayLevel == 1) {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2) {
            $view = 'TutorialBundle:Tutorial:view2a.html.twig';
        }

        return $view;
    }

    /**
     * Shows a part
     *
     * @param Request $request      The request
     * @param string  $categorySlug The slug of the category
     * @param string  $courseSlug   The slug of the course
     * @param string  $partSlug     The slug of the part
     *
     * @return Response
     */
    public function partAction(Request $request, $categorySlug, $courseSlug, $partSlug)
    {
        $data = $this->processPartAction($request, $categorySlug, $courseSlug, $partSlug);

        return $this->render($data['view'], $data['parameters']);
    }

    /**
     * Shows a part
     *
     * @param Request $request      The request
     * @param string  $categorySlug The slug of the category
     * @param string  $courseSlug   The slug of the course
     * @param string  $partSlug     The slug of the part
     *
     * @return array <ul>
     *                   <li>view</li>
     *                   <li>parameters</li>
     *               </ul>
     */
    public function processPartAction(Request $request, $categorySlug, $courseSlug, $partSlug)
    {
        $data = $this->get('simple_it.claire.course.course')->getPartWithComplementaries($categorySlug, $courseSlug, $partSlug);
        $course = $data['course'];
        $part = $data['part'];

        $displayLevel = $course->getDisplayLevel();
        /* Get the Part content (only for 1b or 2c) */
        $formatedContent = null;
        $introduction = null;
        if ($displayLevel === 1 || Part::TYPE_TITLE_3 === $part->getType()) {
            $content = $this->courseService->getPartContent($courseSlug, $partSlug);
            if (null != $content) {
                $formatedContent = $this->courseService->getFormatedContent($content);
            }
        } else {
           $introduction = $this->courseService->getPartIntroduction($courseSlug, $partSlug);
        }
        //TODO Api Route
        $parentPart = null;
        if ($displayLevel === 2 && Part::TYPE_TITLE_3 === $part->getType()) {
            //$parentPart = $this->courseService->getParentPart($part);
        }

        /* Format the tags */
        $tags = $this->courseService->getPartTags($course, $part, $parentPart);

        /* Format the metadatas */
        $metadatas = $this->courseService->getPartMetadatas($course, $part, $parentPart);

        /* Get the pagination */
        $pagination = $this->courseService->getPagination($course, $part, $displayLevel);

        $data['view'] = $this->getPartView($displayLevel, $part);
        $data['parameters'] = array(
                'title' => $part->getTitle(),
                'course' => $course, 'part' => $part,
                'category' => $course->getCategory(),
                'icon' => (isset($metadatas[Metadata::COURSE_METADATA_IMAGE]) ? $metadatas[Metadata::COURSE_METADATA_IMAGE] : null),
                'aggregateRating' => (isset($metadatas[Metadata::COURSE_METADATA_AGGREGATE_RATING]) ? $metadatas[Metadata::COURSE_METADATA_AGGREGATE_RATING] : null),
                'difficulty' => (isset($metadatas[Metadata::COURSE_METADATA_DIFFICULTY]) ? $metadatas[Metadata::COURSE_METADATA_DIFFICULTY] : null),
                'duration' => (isset($metadatas[Metadata::COURSE_METADATA_DURATION]) ? $metadatas[Metadata::COURSE_METADATA_DURATION] : null),
                'timeline' => $this->courseService->getTimeline($course), 'tags' => $tags,
                'updatedAt' => $part->getUpdatedAt(), 'pagination' => $pagination,
                'introduction' => $introduction,
                'toc' => $this->courseService->getDisplayToc($course, $displayLevel, $part),
                'contentHtml' => $formatedContent,
                'license' => (isset($metadatas[Metadata::COURSE_METADATA_LICENSE]) ? $metadatas[Metadata::COURSE_METADATA_LICENSE] : null),
                'description' => (isset($metadatas[Metadata::COURSE_METADATA_DESCRIPTION]) ? $metadatas[Metadata::COURSE_METADATA_DESCRIPTION] : null),
                'authors' => $course->getAuthors()
                );

        return $data;
    }

    /**
     * Get the associated view for a specific context
     *
     * @param integer $displayLevel The level display for the course
     *                              Should be 1 or 2
     * @param Part    $part         The part
     *
     * @return string The associated view
     */
    private function getPartView($displayLevel, Part $part)
    {
        $this->courseService->checkPartDisplayLevelValidity($displayLevel);

        $type = $part->getType();
        if ($displayLevel == 1 && $type == Part::TYPE_TITLE_1) {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        } elseif ($displayLevel == 2 && $type == Part::TYPE_TITLE_2) {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2 && $type == Part::TYPE_TITLE_3) {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        }
        return $view;
    }

    /**
     * Courses homepage
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $options = new ApiRequestOptions();
        $options->setItemsPerPage(9);
        $options->setPageNumber(1);
        $options->addFilter('sort', 'updatedAt desc');

        $requests['courses'] = $this->getClaireApi('courses')->getCourses($options);
        $results = $this->getClaireApi()->getResults($requests);

        $courses = $results['courses'];
        if(is_null($courses) || $courses === false)
        {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(500, 'Oups, la liste des tutoriels n\'a pas pu être générée');
        }

        $data['view'] = 'SimpleITClaireAppBundle:Course:index.html.twig';
        $data['viewParameters'] = array(
            'courses' => $courses->getContent(),
            'categories' => $categories->getContent(),
            'tags' => $tags->getContent()
        );


        return $this->render($data['view'], $data['parameters']);
    }

    /**
     * Create a new course
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new CourseType());

        if($request->isMethod('post'))
        {
            $form->bind($request);

            if($form->isValid())
            {
                $course = $form->getData();
                $course = $this->getCoursesApi()->createCourse($course);

                $slug = $course['reference']['slug'];

                return $this
                    ->redirect(
                        $this
                            ->generateUrl('course_view', array('slug' => $slug)));
            }
        }

        return $this->render('SimpleITClaireAppBundle:Course:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * Edit a course
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $course = $this->getCoursesApi()->getCourse($request->get('slug'));

        $form = $this->createForm(new CourseType(), $course);

        if($request->isMethod('post'))
        {
            $form->bind($request);

            if($form->isValid())
            {
                $course = $form->getData();
                $course = $this->getCoursesApi()->updateCourse($course);

                $slug = $course['reference']['slug'];

                return $this
                    ->redirect(
                        $this
                            ->generateUrl('course_edit', array('slug' => $slug)));
            }
        }

        return $this->render('SimpleITClaireAppBundle:Course:edit.html.twig', array('form' => $form->createView(), 'course' => $course));
    }

    /**
     * List courses
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $parameters = $request->query->all();
        $page = new Page(18);
        $sort = new Sort('updatedAt', Sort::DESC);
        $collectionInformation = new CollectionInformation($page, array($sort), $parameters);
        $courses = $this->get('simple_it.claire.course.course')->get($collectionInformation);

        $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
        $this->viewParameters = array(
            'courses' => $courses
        );
        return $this->generateView($this->view, $this->viewParameters);
    }

    /**
     * Generate the rendered response
     *
     * @param string $view           The view name
     * @param array  $viewParameters Associated view parameters
     *
     * @return Response A Response instance
     */
    protected function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }
}
