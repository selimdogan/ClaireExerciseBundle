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
        if(!$chapterSlug)
        {
            $chapterSlug = $request->get('slug');
        }

        $this->getCoursesApi()->prepareToc($rootSlug);
        $this->getCoursesApi()->prepareCourse($chapterSlug, $rootSlug);
        $tutorial = $this->getCoursesApi()->getResult();

        $tutorial['tutorial'] = $this->get('simpleit.claire.tutorial')->setPagination($tutorial['tutorial'], $tutorial['toc']);

        return $this->render('TutorialBundle:Tutorial:view.html.twig',
            array(
                'tutorial' => $tutorial['tutorial'],
                'toc' => $tutorial['toc']
            )
        );
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
        $this->getCoursesApi()->prepareCourses();
        $tutorials = $this->getCoursesApi()->getData();

        return $this->render('SimpleITClaireAppBundle:Course:list.html.twig', array('courses' => $tutorials));
    }
}
