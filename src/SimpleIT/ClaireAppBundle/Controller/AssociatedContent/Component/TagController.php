<?php


namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\AppBundle\Controller\AppController;

/**
 * Class TagController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagController extends AppController
{

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
}
