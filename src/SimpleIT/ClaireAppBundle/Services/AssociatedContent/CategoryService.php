<?php


namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

use SimpleIT\ApiResourcesBundle\ContentAssociation\CategoryResource;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CategoryRepository;

/**
 * Class CategoryService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * Set categoryRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CategoryRepository $categoryRepository
     */
    public function setCategoryRepository($categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get a list of categories
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll()
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Get a category
     *
     * @param int | string $categoryIdentifier Category id | slug
     *
     * @return CategoryResource
     */
    public function get($categoryIdentifier)
    {
        return $this->categoryRepository->find($categoryIdentifier);
    }
}
