<?php

namespace SimpleIT\ClaireAppBundle\Controller\User\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class CoursesByAuthorController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseByAuthorController extends AppController
{
    /**
     * Get a list of courses for an author
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param int|string            $userIdentifier        User id | slug
     *
     * paramConverter ("collectionInformation",options={"itemsPerPage" = "all", "sort" = "updatedAt"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(CollectionInformation $collectionInformation, $userIdentifier)
    {
        $courses = $this->get('simple_it.claire.user.author')->getAllCourses(
            $userIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:User/Author/Component:viewCoursesByAuthor.html.twig',
            array(
                'courses' => $courses,
                'collectionInformation' => $collectionInformation,
            )
        );
    }
}
