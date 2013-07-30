<?php


namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class TagController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagController extends AppController
{

    /**
     * Get a list of tags
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAll(
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:list.html.twig',
            array('tags' => $tags)
        );
    }

    /**
     * View a tag
     *
     * @param int | string $tagIdentifier Tag id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($tagIdentifier)
    {
        $tag = $this->get('simple_it.claire.associated_content.tag')->get(
            $tagIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:view.html.twig',
            array('tag' => $tag)
        );
    }

    /**
     * List recommended courses
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param mixed                 $tagIdentifier         Tag id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listRecommendedCoursesAction(
        CollectionInformation $collectionInformation,
        $tagIdentifier
    )
    {
        $courses = $this->get('simple_it.claire.associated_content.tag')->getRecommendedCourses(
            $tagIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:listRecommendedCourses.html.twig',
            array('courses' => $courses)
        );
    }

    /**
     * Get a list of courses of a category
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param mixed                 $tagIdentifier         Tag id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCoursesAction(
        CollectionInformation $collectionInformation,
        $tagIdentifier
    )
    {
        $courses = $this->get('simple_it.claire.associated_content.tag')->getAllCourses(
            $tagIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:Course/Course/Component:searchList.html.twig',
            array(
                'courses' => $courses
            )
        );
    }

}
