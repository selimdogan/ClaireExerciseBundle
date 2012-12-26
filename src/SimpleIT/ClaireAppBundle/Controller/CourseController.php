<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use SimpleIT\ClaireAppBundle\Model\Metadata;

use SimpleIT\ClaireAppBundle\Model\CourseFactory;

use SimpleIT\ClaireAppBundle\Services\CourseService;

use SimpleIT\Utils\ArrayUtils;

use SimpleIT\AppBundle\Services\ApiService;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Course controller
 */
class CourseController extends BaseController
{
    /** @var Service The course service*/
    private $courseService;

    /**
     * View a course
     *
     * @param Request $request      Request
     * @param string  $categorySlug The slug for the category
     * @param string  $courseSlug   The slug for the course
     *
     * @return Response
     */
    public function readAction(Request $request, $categorySlug, $courseSlug)
    {
        $this->courseService = $this->get('simpleit.claire.course');

        $course = $this->courseService->getCourseByCategory($courseSlug, $categorySlug);
        $course = $this->courseService->getCourseComplementaries($courseSlug, $course);
        $timeline = $this->courseService->getTimeline($course);

        $displayLevel = $course->getDisplayLevel();

        $metadatas = $course->getMetadatas();

        return $this
        ->render($this->getView($displayLevel),
            array('title' => $course->getTitle(),
                'course' => $course,
                'category' => $course->getCategory(),
                'icon' => ArrayUtils::getValue($metadatas, Metadata::COURSE_METADATA_IMAGE),
                'aggregateRating' => ArrayUtils::getValue($metadatas, Metadata::COURSE_METADATA_AGGREGATE_RATING),
                'difficulty' => ArrayUtils::getValue($metadatas, Metadata::COURSE_METADATA_DIFFICULTY),
                //FIXME DateInterval
                'duration' => ArrayUtils::getValue($metadatas, Metadata::COURSE_METADATA_DURATION),
                'timeline' => $this->courseService->getTimeline($course),
                'tags' => $course->getTags(),
                'updatedAt' => $course->getUpdatedAt(),
                'introduction' => $course->getIntroduction(),
                'toc' => $this->courseService->getDisplayToc($course, $displayLevel),
                'license' => ArrayUtils::getValue((array) $metadatas, Metadata::COURSE_METADATA_LICENSE),
                'description' => ArrayUtils::getValue((array) $metadatas, Metadata::COURSE_METADATA_DESCRIPTION)
        ));
    }

    //FIXME To put on home page of SdZ v4
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

        $requests['courses'] = $this->getClaireApi('courses')
            ->getCourses($options);

        $optionsCategories = new ApiRequestOptions();
        $optionsCategories->setItemsPerPage(3);
        $optionsCategories->setPageNumber(1);

        $requests['categories'] = $this->getClaireApi('categories')
            ->getCategories($optionsCategories);

        $optionsTags = new ApiRequestOptions();
        $optionsTags->setPageNumber(1);

        $requests['tags'] = $this->getClaireApi('categories')
            ->getTags($optionsTags);

        $results = $this->getClaireApi()->getResults($requests);

        $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
        $this->viewParameters = array(
        'courses' => $results['courses']->getContent(),
        'categories' => $results['categories']->getContent(),
        'tags' => $results['tags']->getContent()
        );
        return $this->generateView($this->view, $this->viewParameters);
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

        if ($request->isMethod('post')) {
            $form->bind($request);

            if ($form->isValid()) {
                $course = $form->getData();
                $course = $this->getCoursesApi()->createCourse($course);

                $slug = $course['reference']['slug'];
                return $this
                    ->redirect(
                        $this
                            ->generateUrl('course_view', array('slug' => $slug)));
            }
        }

        return $this
            ->render('SimpleITClaireAppBundle:Course:create.html.twig',
                array('form' => $form->createView()));
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

        if ($request->isMethod('post')) {
            $form->bind($request);

            if ($form->isValid()) {
                $course = $form->getData();
                $course = $this->getCoursesApi()->updateCourse($course);

                $slug = $course['reference']['slug'];
                return $this
                    ->redirect(
                        $this
                            ->generateUrl('course_edit', array('slug' => $slug)));
            }
        }

        return $this
            ->render('SimpleITClaireAppBundle:Course:edit.html.twig',
                array('form' => $form->createView(), 'course' => $course));
    }



    /**
     * Get the associated view
     *
     * @param int $displayLevel The display level of the course
     *
     * @return string The view template
     */
    private function getView($displayLevel)
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

//     /**
//      * Make Breadcrumb
//      *
//      * @param array $baseCourse Base Course
//      * @param array $category   Category
//      * @param array $course     Course
//      * @param array $toc        TOC
//      */
//     private function makeBreadcrumb($baseCourse, $category, $course, $toc)
//     {
//         $points = array('course' => 0, 'title-1' => 1, 'title-2' => 2,
//         'title-3' => 3,
//         );

//         // BreadCrumb
//         $breadcrumb = $this->get('apy_breadcrumb_trail');

//         $breadcrumb->add($category['title'], 'SimpleIT_Claire_categories_view',
//                 array('slug' => $category['slug']));

//         if ($baseCourse['slug'] != $course['slug']) {
//             $breadcrumb
//                 ->add($baseCourse['title'], 'course_view',
//                     array('categorySlug' => $category['slug'],
//                     'rootSlug' => $baseCourse['slug'],
//                     ));
//         }

//         if (!empty($toc)) {
//             foreach ($toc as $key => $element) {
//                 if ($element['slug'] == $course['slug']) {
//                     $types = array('title-1', $element['type']);
//                     for ($i = $key - 1; $i >= 0; $i--) {
//                         if (!in_array($toc[$i]['type'], $types)
//                             && $points[$toc[$i]['type']]
//                                 < $points[$element['type']]) {
//                             $types[] = $toc[$i]['type'];
//                             $breadcrumb
//                                 ->add($toc[$i]['title'], 'course_view',
//                                     array('categorySlug' => $category['slug'],
//                                     'rootSlug' => $baseCourse['slug'],
//                                     'titleSlug' => $toc[$i]['slug']
//                                     ));
//                         }
//                     }
//                     break;
//                 }
//             }
//         }
//         $breadcrumb->add($course['title']);
//     }

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

        $options = new ApiRequestOptions();
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->addFilters($parameters, array('sort'));

        $coursesRequest = $this->getClaireApi('courses')->getCourses($options);
        $courses = $this->getClaireApi()->getResult($coursesRequest);

        $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
        $this->viewParameters = array('courses' => $courses->getContent(),
        'appPager' => $courses->getPager()
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
