<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Course controller
 */
class CourseController extends BaseController
{
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
                return $this->redirect($this->generateUrl('course_view', array('slug' => $slug)));
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
                return $this->redirect($this->generateUrl('course_edit', array('slug' => $slug)));
            }
        }

        return $this->render('SimpleITClaireAppBundle:Course:edit.html.twig', array('form' => $form->createView(), 'course' => $course));
    }

    /**
     * View a course
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function readAction(Request $request, $categorySlug, $rootSlug, $titleSlug = null)
    {
        $configuration = array(
            '1' => array(null, 'title-1'),
            '2' => array(null, 'title-2', 'title-3')
        );
        // Category API
        $categoryRequest = $this->getClaireApi('categories')->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);
        $this->checkObjectFound($category);

        // Get toc
        $requests['courseToc'] = $this->getClaireApi('courses')->getCourseToc($rootSlug);

        // Root Course API
        $requests['course'] = $this->getClaireApi('courses')->getCourse($rootSlug, null, null);

        $results = $this->getClaireApi()->getResults($requests);

        $this->checkObjectFound($results['course']);
        $baseCourse = $results['course']->getContent();
        $titleType = $this->get('simpleit.claire.course')->getTitleType($titleSlug, $results['courseToc']->getContent());

        // Course API
        $courseRequest = $this->getClaireApi('courses')->getCourse($rootSlug, $titleSlug, $titleType);
        $course = $this->getClaireApi()->getResult($courseRequest);

        $this->checkObjectFound($course);

        $tmpCat = $category->getContent();
        if($baseCourse['category']['id'] != $tmpCat['id'])
        {
            throw $this->createNotFoundException('Unable to find this course in this category');
        }

        $toc = $results['courseToc']->getContent();

        // Alterate
        $course = $this->get('simpleit.claire.course')->setPagination(
                $course->getContent(),
                $results['courseToc']->getContent(),
                ($baseCourse['displayLevel'] == 1) ? array('title-2', 'title-3') : array('title-1')
            );
        $course['type'] = $titleType;
        $date = new \DateTime();
        $course['updatedAt'] = $date->setTimestamp(strtotime($course['updatedAt']));

        // Verification titletype
        $currentConfig = $configuration[$baseCourse['displayLevel']];
        if (!in_array($titleType, $currentConfig))
        {
            throw $this->createNotFoundException('Title not accepted !');
        }

        // Other informations
        if ($currentConfig[count($currentConfig) - 1]== $titleType)
        {
            $requests['courseContent'] = $this->getClaireApi('courses')->getCourseContent($rootSlug, $titleSlug, $titleType);
        }

        $request = $this->getClaireApi('courses')->getCourseTimeline($rootSlug);
        $timeline = $this->getClaireApi()->getResult($request);

        // Requesting
        $requests['courseTags'] = $this->getClaireApi('courses')->getCourseTags($rootSlug);
        $requests['courseMetadatas'] = $this->getClaireApi('courses')->getCourseMetadatas($rootSlug);

        $requests['courseIntroduction'] = $this->getClaireApi('courses')->getIntroduction($rootSlug, $titleSlug, $titleType);
        $results = $this->getClaireApi()->getResults($requests);
        $course['introduction'] = $results['courseIntroduction']->getContent();

        // Breadcrumb
        $this->makeBreadcrumb(
                $baseCourse,
                $category->getContent(),
                $course,
                $toc);

        // Restrict TOC
        $toc = $this->get('simpleit.claire.course')->restrictTocForTitle(
                $course,
                $toc,
                (is_null($titleType) && $baseCourse['displayLevel'] == 1)  ? 'course' : $titleType);

        return $this->render('TutorialBundle:Tutorial:view.html.twig',
            array(
                'course' => $course,
                'baseCourse' => $baseCourse,
                'toc' => $toc,
                'tags' => $results['courseTags']->getContent(),
                'contentHtml' => (isset($requests['courseContent'])) ? $results['courseContent']->getContent() : '',
                'timeline' => $timeline->getContent(),
                'rootSlug' => $rootSlug,
                'category' => $category->getContent(),
                'difficulty' => $this->getOneMetadata('difficulty', $results['courseMetadatas']->getContent()),
                'duration' => $this->getOneMetadata('duration', $results['courseMetadatas']->getContent()),
                'licence' => $this->getOneMetadata('license', $results['courseMetadatas']->getContent()),
                'description' => $this->getOneMetadata('description ', $results['courseMetadatas']->getContent()),
                'rate' => $this->getOneMetadata('aggregateRating', $results['courseMetadatas']->getContent()),
                'icon' => $this->getOneMetadata('image', $results['courseMetadatas']->getContent()),
                'titleType' => $titleType
            )
        );
    }

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
       $points = array(
            'course' => 0,
            'title-1' => 1,
            'title-2' => 2,
            'title-3' => 3,
        );

        // BreadCrumb
        $breadcrumb = $this->get('apy_breadcrumb_trail');
        $breadcrumb->add($category['title'], 'SimpleIT_Claire_categories_view', array('slug' => $category['slug']));

        if ($baseCourse['slug'] != $course['slug'])
        {
            $breadcrumb->add($baseCourse['title'], 'course_view',
                    array(
                        'categorySlug' => $category['slug'],
                        'rootSlug'     => $baseCourse['slug'],
                        ));
        }

        if (!empty($toc))
        {
            foreach($toc as $key => $element)
            {
                if ($element['slug'] == $course['slug'])
                {
                    $types = array('title-1', $element['type']);
                    for($i = $key - 1; $i >= 0; $i--)
                    {
                        if (!in_array($toc[$i]['type'], $types) && $points[$toc[$i]['type']] < $points[$element['type']])
                        {
                            $types[] = $toc[$i]['type'];
                            $breadcrumb->add($toc[$i]['title'],
                                    'course_view',
                                    array(
                                        'categorySlug' => $category['slug'],
                                        'rootSlug'     => $baseCourse['slug'],
                                        'titleSlug'    => $toc[$i]['slug']
                                        )
                                    );
                        }
                    }
                    break;
                }
            }
        }
        $breadcrumb->add($course['title']);
    }

    /**
     * Get One metadata
     *
     * @param string $key  Key to search
     * @param array  $list Array list of metadata
     *
     * @return string | null
     */
    private function getOneMetadata($key, $metadatas)
    {
        $value = '';

        if (is_array($metadatas))
        {
            foreach($metadatas as $metadata)
            {
                if ($metadata['key'] == $key)
                {
                    $value = $metadata['value'];
                    break;
                }
            }
        }

        return $value;
    }

    /**
     * List courses
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $parameters = $request->query->all();

        $options = new ApiRequestOptions();
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->bindFilter($parameters, array('sort'));

        $coursesRequest = $this->getClaireApi('courses')->getCourses($options);
        $courses = $this->getClaireApi()->getResult($coursesRequest);

        $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
        $this->viewParameters = array(
            'courses' => $courses->getContent(),
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
