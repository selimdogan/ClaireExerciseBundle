<?php


namespace SimpleIT\ClaireAppBundle\Controller\User\Component;

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

        return $this->render('SimpleITClaireAppBundle:User/Author/Component:viewAuthorsByCourse.html.twig', array('authors' => $authors));
    }

    /**
     * Edit authors
     *
     * @param Request         $request          Request
     * @param integer |string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editListAction(Request $request, $courseIdentifier)
    {
        $authors = array();

        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $authors = $this->get('simple_it.claire.user.author')->getAllByCourse(
                $courseIdentifier
            );
        }
        $authorsString = '';
        foreach ($authors as $author) {
            if ($authorsString != '') {
                $authorsString .= ',';
            }
            $authorsString .= $author->getUsername();
        }

        return $this->render(
            'SimpleITClaireAppBundle:User/Author/Component:editByCourse.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'authors'          => $authorsString
            )
        );
    }
}
