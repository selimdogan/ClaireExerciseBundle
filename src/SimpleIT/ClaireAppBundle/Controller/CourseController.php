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
    public function readAction(Request $request)
    {
        $rootSlug = $request->get('slug');
        $chapterSlug = $request->get('chapterSlug', false);

        $this->getCoursesApi()->prepareToc($rootSlug);
        $this->getCoursesApi()->prepareCourse($rootSlug, $chapterSlug);
        $this->getCoursesApi()->prepareMetadatas($rootSlug);
        $result = $this->getCoursesApi()->getResult();

        $result['course'] = $this->get('simpleit.claire.course')->setPagination($result['course'], $result['toc']);

        return $this->render('TutorialBundle:Tutorial:view.html.twig',
            array(
                'course' => $result['course'],
                'difficulty' => $this->getOneMetadata('CreativeWork/difficulty', $result['metadatas']),
                'duration' => $this->getOneMetadata('CreativeWork/duration', $result['metadatas']),
                'licence' => $this->getOneMetadata('CreativeWork/license', $result['metadatas']),
                'description' => $this->getOneMetadata('Thing/Description ', $result['metadatas']),
                'rate' => $this->getOneMetadata('CreativeWork/aggregateRating', $result['metadatas']),
                'icon' => $this->getOneMetadata('Thing/image', $result['metadatas']),
                'toc' => $result['toc'],
            )
        );
    }

    /**
     * Get One metadata
     *
     * @param type  $key  Key to search
     * @param array $list Array list of metadata
     *
     * @return string | null
     */
    private function getOneMetadata($key, array $metadatas)
    {
        $value = '';

        foreach($metadatas as $metadata)
        {
            if ($metadata['key'] == $key)
            {
                $value = $metadata['value'];
                break;
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
        $this->getCoursesApi()->getCourses();
        $courses = $this->getCoursesApi()->getData();

        return $this->render('SimpleITClaireAppBundle:Course:list.html.twig', array('courses' => $courses));
    }
}
