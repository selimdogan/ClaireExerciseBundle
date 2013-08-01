<?php


namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class TagByCategoryRepository
 * 
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByCategoryRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'categories/{categoryIdentifier}/tags/{tagIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource';

    /**
     * Find all categories
     *
     * @param int |string           $categoryIdentifier    Category id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     * @cache
     */
    public function findAll($categoryIdentifier, CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(
            array('categoryIdentifier' => $categoryIdentifier),
            $collectionInformation
        );
    }
}
