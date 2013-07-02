<?php


namespace SimpleIT\ClaireAppBundle\Controller\User\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorByCourse
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AuthorByCourseController extends AppController
{
    /**
     * Edit authors
     *
     * @param Request         $request Request
     * @param integer |string $courseIdentifier
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editListAction(Request $request, $courseIdentifier)
    {
        $authors = array();

        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $authors = $this->get('simple_it.claire.user.author')->getAllByCourse($courseIdentifier);
        }

        $form = $this->createFormBuilder($authors)
//            ->add('authors')
            ->getForm();

//        if (RequestUtils::METHOD_POST == $request->getMethod()) {
//            $form->bind($request);
//            if ($form->isValid()) {
//                $authors = $this->get('simple_it.claire.user.author')->save(
//                    $courseIdentifier,
//                    $authors
//                );
//            }
//        }

        return $this->render(
            'SimpleITClaireAppBundle:User/Author/Component:editByCourse.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'authors'          => $authors,
                'form'             => $form->createView()
            )
        );
    }
}
