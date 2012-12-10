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
    public function readAction(Request $request, $categorySlug, $rootSlug, $titleType = 'title-1', $titleSlug = null)
    {
        // Verification titletype
        if (!in_array($titleType, array('title-2', 'title-3')))
        {
            $titleType = 'title-1';
        }

        // Category API
        $this->getCategoriesApi()->prepareCategory($categorySlug);
        $result = $this->getCategoriesApi()->getResult();

        // Category exist
        if (!isset($result['category']) || empty($result['category']))
        {
            throw $this->createNotFoundException('Category not found !');
        }
        $category = $result['category'];

        // Course API
        $this->getCoursesApi()->prepareCourse($rootSlug, $titleSlug, $titleType);
        $result = $this->getCoursesApi()->getResult();

        // Course exist
        if (!isset($result['course']) || empty($result['course']))
        {
            throw $this->createNotFoundException('Course not found !');
        }
        $course = $result['course'];

        // Other informations
        if ('title-3' == $titleType)
        {
            $this->getCoursesApi()->prepareCourseContent($rootSlug, $titleSlug, $titleType);
        }
        $this->getCoursesApi()->prepareCourseTags($rootSlug);
        $this->getCoursesApi()->prepareToc($rootSlug);
        $this->getCoursesApi()->prepareMetadatas($rootSlug);
        $this->getCoursesApi()->prepareTimeline($rootSlug);
        $result = $this->getCoursesApi()->getResult();

        $course = $this->get('simpleit.claire.course')->setPagination($course, $result['toc']);

        return $this->render('TutorialBundle:Tutorial:view.html.twig',
            array(
                'course' => $course,
                'toc' => $result['toc'],
                'tags' => $result['tags'],
                'content' => (isset($result['content'])) ? $result['content'] : '',
                'timeline' => $result['timeline']['toc'],
                'rootSlug' => $rootSlug,
                'category' => $category,
                'difficulty' => $this->getOneMetadata('CreativeWork/difficulty', $result['metadatas']),
                'duration' => $this->getOneMetadata('CreativeWork/duration', $result['metadatas']),
                'licence' => $this->getOneMetadata('CreativeWork/license', $result['metadatas']),
                'description' => $this->getOneMetadata('Thing/Description ', $result['metadatas']),
                'rate' => $this->getOneMetadata('CreativeWork/aggregateRating', $result['metadatas']),
                'icon' => $this->getOneMetadata('Thing/image', $result['metadatas']),
            )
        );
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
        $this->getCoursesApi()->prepareCourses();
        $courses = $this->getCoursesApi()->getData();

        return $this->render('SimpleITClaireAppBundle:Course:list.html.twig', array('courses' => $courses));
    }
}
