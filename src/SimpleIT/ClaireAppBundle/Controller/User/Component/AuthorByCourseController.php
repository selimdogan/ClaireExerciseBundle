<?php


namespace SimpleIT\ClaireAppBundle\Controller\User\Component;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorByCourse
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AuthorByCourseController extends AppController
{
    /**
     * View a list of author of a course
     *
     * @param int |string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($courseIdentifier)
    {
        $authors = $this->get('simple_it.claire.user.author')->getAllByCourse($courseIdentifier);

        return $this->render(
            'SimpleITClaireAppBundle:User/Author/Component:viewAuthorsByCourse.html.twig',
            array('authors' => $authors)
        );
    }

    /**
     * Edit a list of authors
     *
     * @param Request               $request               Request
     * @param CollectionInformation $collectionInformation Collection information
     * @param int                   $courseId              Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editViewAction(
        Request $request,
        CollectionInformation $collectionInformation,
        $courseId
    )
    {
        $authors = $this->get('simple_it.claire.user.author')->getAllByCourseToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT),
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:User/Author/Component:editByCourse.html.twig',
            array('authors' => $authors)
        );
    }

    /**
     * View the list of the course's authors for different status
     *
     * @param Request               $request               Request
     * @param int                   $courseId              Course id
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewToEditAction(
        Request $request,
        $courseId,
        CollectionInformation $collectionInformation
    )
    {
        $authors = $this->get('simple_it.claire.user.author')->getAllByCourseToEdit(
            $courseId,
            $request->get(CourseResource::STATUS, CourseResource::STATUS_DRAFT),
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:User/Author/Component:viewAuthorsByCourse.html.twig',
            array('authors' => $authors)
        );
    }
}
