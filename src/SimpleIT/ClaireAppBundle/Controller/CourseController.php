<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Course controller
 */
class CourseController extends CourseBaseController
{

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
        // Category API
        $categoryRequest = $this->getClaireApi('categories')
            ->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);
        $this->checkObjectFound($category);

        // Course API
        $courseRequest = $this->getClaireApi('courses')->getCourse($courseSlug);
        $course = $this->getClaireApi()->getResult($courseRequest);
        $this->checkObjectFound($course);

        // Check category
        $category = $category->getContent();
        $course = $course->getContent();
        if ($course['category']['id'] != $category['id']) {
            throw $this
                ->createNotFoundException(
                    'Unable to find this course in this category');
        }

        // Requesting
        $requests['courseToc'] = $this->getClaireApi('courses')
            ->getCourseToc($courseSlug);
        $requests['courseIntroduction'] = $this->getClaireApi('courses')
            ->getIntroduction($courseSlug);
        $requests['courseTags'] = $this->getClaireApi('courses')
            ->getCourseTags($courseSlug);
        $requests['courseMetadatas'] = $this->getClaireApi('courses')
            ->getCourseMetadatas($courseSlug);

        $results = $this->getClaireApi()->getResults($requests);
        $tags = $results['courseTags']->getContent();
        $toc = $results['courseToc']->getContent();
        $introduction = $results['courseIntroduction']->getContent();

        $metadatas = $results['courseMetadatas']->getContent();

        //FIXME phpUtils
        $date = new \DateTime();
        $course['updatedAt'] = $date
            ->setTimestamp(strtotime($course['updatedAt']));

        //FIXME phpUtils
        $durationDate = new \DateTime();
        $duration = $durationDate
            ->setTimestamp(
                strtotime(
                    $this->getOneMetadata('duration', $metadatas)));

        $displayLevel = $course['displayLevel'];

        // Breadcrumb
        $this->makeBreadcrumb($course, $category, $course, $toc);

        //FIXME
        $duration = '';
        return $this
            ->render($this->getView($displayLevel),
                array('title' => $course['title'],
                        'course' => $course,
                        'category' => $category,
                        'aggregateRating' => $this->getOneMetadata('aggregateRating', $metadatas),
                        'icon' => $this->getOneMetadata('image', $metadatas),
                        'updatedAt' => $course['updatedAt'],
                        'difficulty' => $this->getOneMetadata('difficulty', $metadatas),
                        'duration' => $duration,
                        'description' => $this->getOneMetadata('description ', $metadatas),
                        'tags' => $tags,
                        'licence' => $this->getOneMetadata('license', $metadatas),
                        'introduction' => $introduction,
                        'timeline' => $this->getTimeline($toc, $displayLevel),
                        'toc' => $this->getDisplayToc($toc, $displayLevel)
                ));
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
        $this->checkCourseDisplayLevelValidity($displayLevel);

        if ($displayLevel == 0) {
            $view = 'TutorialBundle:Tutorial:view00.html.twig';
        } elseif ($displayLevel == 1) {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2) {
            $view = 'TutorialBundle:Tutorial:view2a.html.twig';
        }

        return $view;
    }

//     private function prepareToc($toc, $displayLevel)
//     {
//         $displayToc = array();
//         $i = 0;

//         if ($displayLevel == 0 || $displayLevel == 1) {
//             foreach ($toc as $part) {
//                 if ($part['type'] == 'title-1') {
//                     $displayToc[$i] = $part;
//                     $i++;
//                 }
//             }
//         } else {
//             $displayTocLevel = array('title-1' => 0, 'title-2' => 1,
//             'title-3' => 2, 'title-4' => 3, 'title-5' => 4
//             );
//             foreach ($toc as $part) {
//                 $part['level'] = $displayTocLevel[$part['type']];
//                 $displayToc[$i] = $part;
//                 $i++;
//             }
//         }
//         return $displayToc;
//     }

//     /**
//      *
//      * @param type $toc
//      * @param type $displayLevel
//      * @param type $currentPartTitle
//      * @return type
//      */
//     private function getDisplayToc($toc, $displayLevel, $currentPartTitle = null)
//     {
//         $neededTypes = array();
//         if ($displayLevel == 0 || $displayLevel == 1) {
//             $neededTypes = $this->neededTypesLevel01;
//         } else {
//             $neededTypes = $this->neededTypesLevel2;
//         }
//         $displayToc = array();
//         $i = 0;
//         $isOver = false;

//         foreach ($toc as $part) {
//             if ($part['type'] == 'title-1') {
//                 $part['isOver'] = $isOver;
//                 $timeline[$i] = $part;
//                 if ($part['title'] == $currentPartTitle) {
//                     $isOver = true;
//                 }
//                 $i++;
//             }
//         }
//         return $timeline;
//     }

    /**
     * Make Breadcrumb
     *
     * @param array $baseCourse Base Course
     * @param array $category   Category
     * @param array $course     Course
     * @param array $toc        TOC
     */
    private function makeBreadcrumb($baseCourse, $category, $course, $toc)
    {
        $points = array('course' => 0, 'title-1' => 1, 'title-2' => 2,
        'title-3' => 3,
        );

        // BreadCrumb
        $breadcrumb = $this->get('apy_breadcrumb_trail');
        $breadcrumb
            ->add($category['title'], 'SimpleIT_Claire_categories_view',
                array('slug' => $category['slug']));

        if ($baseCourse['slug'] != $course['slug']) {
            $breadcrumb
                ->add($baseCourse['title'], 'course_view',
                    array('categorySlug' => $category['slug'],
                    'rootSlug' => $baseCourse['slug'],
                    ));
        }

        if (!empty($toc)) {
            foreach ($toc as $key => $element) {
                if ($element['slug'] == $course['slug']) {
                    $types = array('title-1', $element['type']);
                    for ($i = $key - 1; $i >= 0; $i--) {
                        if (!in_array($toc[$i]['type'], $types)
                            && $points[$toc[$i]['type']]
                                < $points[$element['type']]) {
                            $types[] = $toc[$i]['type'];
                            $breadcrumb
                                ->add($toc[$i]['title'], 'course_view',
                                    array('categorySlug' => $category['slug'],
                                    'rootSlug' => $baseCourse['slug'],
                                    'titleSlug' => $toc[$i]['slug']
                                    ));
                        }
                    }
                    break;
                }
            }
        }
        $breadcrumb->add($course['title']);
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
