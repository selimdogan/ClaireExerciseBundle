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
    public function readAction(Request $request, $rootSlug, $title1Slug = false, $title2Slug = false, $title3Slug = false)
    {
        $slug = ((!empty($title3Slug)) ? $title3Slug : (!empty($title2Slug)) ? $title2Slug : $title1Slug);
        $type = ((!empty($title3Slug)) ? 'title-3' : (!empty($title2Slug)) ? 'title-2' : 'title-1');

        $this->getCoursesApi()->prepareCourse($rootSlug, $slug, $type);
        $this->getCoursesApi()->prepareCourseHtml($rootSlug, $slug, $type);
        $this->getCoursesApi()->prepareCourseTags($rootSlug);
        $this->getCoursesApi()->prepareToc($rootSlug);
        $this->getCoursesApi()->prepareMetadatas($rootSlug);
        $this->getCoursesApi()->prepareTimeline($rootSlug);
        $result = $this->getCoursesApi()->getResult();

        $result['course'] = $this->get('simpleit.claire.course')->setPagination($result['course'], $result['toc']);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
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
                'tags' => $result['tags'],
                'timeline' => $result['timeline']['toc'],
                'content' => $result['content'],
                'rootSlug' => $rootSlug,
                'title1Slug' => $title1Slug,
                'title2Slug' => $title2Slug,
                'title3Slug' => $title3Slug,
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
