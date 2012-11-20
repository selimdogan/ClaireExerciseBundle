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
    public function viewAction(Request $request)
    {
        $course = $this->getCoursesApi()->getCourse($request->get('slug'));

        return $this->render('SimpleITClaireAppBundle:Course:view.html.twig', array('course' => $course));
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
        $courses = $this->getCoursesApi()->getCourses();

        return $this->render('SimpleITClaireAppBundle:Course:list.html.twig', array('courses' => $courses));
    }
}
