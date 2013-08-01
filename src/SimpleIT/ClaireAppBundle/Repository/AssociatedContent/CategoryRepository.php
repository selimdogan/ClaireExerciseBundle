<?php

namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CategoryRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'categories/{categoryIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource';

    /**
     * Find all categories
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     * @cache
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(array(), $collectionInformation);
    }

    /**
     * Find a category
     *
     * @param int | string $categoryIdentifier Category id | slug
     *
     * @return CategoryResource
     * @cache
     */
    public function find($categoryIdentifier)
    {
        return parent::findResource(array('categoryIdentifier' => $categoryIdentifier));
    }
}
