<?php

namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagByCourseController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByCourseController extends AppController
{
    /**
     * Get a list of tags of a course
     *
     * @param int |string $courseIdentifier Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($courseIdentifier)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourse(
            $courseIdentifier
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:viewByCourse.html.twig',
            array('tags' => $tags)
        );
    }

    /**
     * Edit tags
     *
     * @param Request         $request                  Request
     * @param integer |string $courseIdentifier         Course id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editListAction(Request $request, $courseIdentifier)
    {
        $tags = array();
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCourse(
                $courseIdentifier
            );
        }
        $tagsString = '';
        foreach ($tags as $tag) {
            if ($tagsString != '') {
                $tagsString .= ',';
            }

            $tagsString .= $tag->getName();
        }

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:editByCourse.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'tags'             => $tagsString
            )
        );
    }
}
