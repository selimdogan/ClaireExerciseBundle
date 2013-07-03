<?php

namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagByPartController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByPartController extends AppController
{
    /**
     * Edit tags
     *
     * @param Request         $request          Request
     * @param integer |string $courseIdentifier Course id | slug
     * @param integer |string $partIdentifier   Part id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editListAction(Request $request, $courseIdentifier, $partIdentifier)
    {
        $tags = array();
        if (RequestUtils::METHOD_GET == $request->getMethod()) {
            $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByPart(
                $courseIdentifier,
                $partIdentifier
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
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:editByPart.html.twig',
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier,
                'tags'             => $tagsString
            )
        );
    }
}
