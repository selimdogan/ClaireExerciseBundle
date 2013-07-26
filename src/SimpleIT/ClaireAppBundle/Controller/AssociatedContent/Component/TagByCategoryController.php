<?php


namespace SimpleIT\ClaireAppBundle\Controller\AssociatedContent\Component;

use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class TagByCategoryController
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByCategoryController extends AppController
{
    /**
     * Get a list of categories
     *
     * @param CollectionInformation $collectionInformation Collection information
     * @param int | string          $categoryIdentifier    Category id | slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(CollectionInformation $collectionInformation, $categoryIdentifier)
    {
        $tags = $this->get('simple_it.claire.associated_content.tag')->getAllByCategory(
            $categoryIdentifier,
            $collectionInformation
        );

        return $this->render(
            'SimpleITClaireAppBundle:AssociatedContent/Tag/Component:list.html.twig',
            array('categoryIdentifier' => $categoryIdentifier, 'tags' => $tags)
        );
    }
}
