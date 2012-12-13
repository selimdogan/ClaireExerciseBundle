<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;

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
        // Category API
        $categoryRequest = $this->getCategoryRouteService()->getCategory($categorySlug);
        $category = $this->getApiService()->getResult($categoryRequest);
        $this->checkObjectFound($category);

        // Get toc
        $requests['courseToc'] = $this->getCourseRouteService()->getCourseToc($rootSlug);
        $results = $this->getApiService()->getResult($requests);
        $toc = $results['courseToc']->getContent();
        $titleType = $this->get('simpleit.claire.course')->getTitleType($titleSlug, $toc);

        // Root Course API
        $courseRequest = $this->getCourseRouteService()->getCourse($rootSlug, null, null);
        $baseCourse = $this->getApiService()->getResult($courseRequest);
        $this->checkObjectFound($baseCourse);

        // Course API
        $courseRequest = $this->getCourseRouteService()->getCourse($rootSlug, $titleSlug, $titleType);
        $course = $this->getApiService()->getResult($courseRequest);
        $this->checkObjectFound($course);

        // Verification titletype
        if (!in_array($titleType, array(null, 'title-2', 'title-3')))
        {
            throw $this->createNotFoundException('Title 1 not accepted !');
        }

        // Other informations
        if ('title-3' == $titleType)
        {
            $requests['courseContent'] = $this->getCourseRouteService()->getCourseContent($rootSlug, $titleSlug, $titleType, 'text/html');
        }

        // Requesting
        $requests['courseTags'] = $this->getCourseRouteService()->getCourseTags($rootSlug);
        $requests['courseMetadatas'] = $this->getCourseRouteService()->getCourseMetadatas($rootSlug);
        $requests['courseTimeline'] = $this->getCourseRouteService()->getCourseTimeline($rootSlug);
        $results = $this->getApiService()->getResult($requests);

        // Alterate
        $course = $this->get('simpleit.claire.course')->setPagination($course->getContent(), $toc);
        $course['type'] = $titleType;
        $date = new \DateTime();
        $course['updatedAt'] = $date->setTimestamp(strtotime($course['updatedAt']));
        $this->makeBreadcrumb($baseCourse->getContent(), $category->getContent(), $course, $toc);

        // Restrict TOC
        $toc = $this->get('simpleit.claire.course')->restrictTocForTitle2($course, $toc);

        return $this->render('TutorialBundle:Tutorial:viewBig.html.twig',
            array(
                'course' => $course,
                'baseCourse' => $baseCourse->getContent(),
                'toc' => $toc,
                'tags' => $results['courseTags']->getContent(),
                'contentHtml' => (isset($requests['courseContent'])) ? $results['courseContent']->getContent() : '',
                'timeline' => $results['courseTimeline']->getContent(),
                'rootSlug' => $rootSlug,
                'category' => $category->getContent(),
                'difficulty' => $this->getOneMetadata('CreativeWork/difficulty', $results['courseMetadatas']->getContent()),
                'duration' => $this->getOneMetadata('CreativeWork/duration', $results['courseMetadatas']->getContent()),
                'licence' => $this->getOneMetadata('CreativeWork/license', $results['courseMetadatas']->getContent()),
                'description' => $this->getOneMetadata('Thing/Description ', $results['courseMetadatas']->getContent()),
                'rate' => $this->getOneMetadata('CreativeWork/aggregateRating', $results['courseMetadatas']->getContent()),
                'icon' => $this->getOneMetadata('Thing/image', $results['courseMetadatas']->getContent()),
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
                        if (!in_array($toc[$i]['type'], $types))
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
    public function listAction()
    {
        $coursesRequest = $this->getCourseRouteService()->getCourses();
        $courses = $this->getApiService()->getResult($coursesRequest);

        return $this->render('SimpleITClaireAppBundle:Course:list.html.twig', array('courses' => $courses->getContent()));
    }
}
