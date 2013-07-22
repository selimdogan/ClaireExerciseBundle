<?php


namespace SimpleIT\ClaireAppBundle\Controller\Course\Component;

use SimpleIT\AppBundle\Controller\AppController;

/**
 * Class CourseController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseController extends AppController
{
    /**
     * View introduction
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewIntroductionAction($courseIdentifier)
    {
        $introduction = $this->get('simple_it.claire.course.course')->getIntroduction(
            $courseIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $introduction)
        );
    }

    /**
     * View timeline
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int          $displayLevel     Display level
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTimelineAction($courseIdentifier, $displayLevel)
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewTimeline.html.twig',
            array('toc' => $toc, 'displayLevel' => $displayLevel)
        );
    }

    /**
     * View table of content
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int          $displayLevel     Display level
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTocAction($courseIdentifier, $displayLevel)
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewToc.html.twig',
            array('toc' => $toc, 'displayLevel' => $displayLevel)
        );
    }

    /**
     * View pagination
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int          $displayLevel     Display level
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewPaginationAction($courseIdentifier, $displayLevel)
    {
        $toc = $this->get('simple_it.claire.course.course')->getToc($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewPagination.html.twig',
            array('toc' => $toc, 'displayLevel' => $displayLevel, 'identifier' => $courseIdentifier)
        );
    }

    /**
     * View content
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewContentAction($courseIdentifier)
    {
        $content = $this->get('simple_it.claire.course.course')->getContent($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:viewContent.html.twig',
            array('content' => $content)
        );
    }
}
