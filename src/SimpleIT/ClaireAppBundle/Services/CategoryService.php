<?php
namespace SimpleIT\ClaireAppBundle\Services;

/**
 * Class CategoryService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryService
{
    /** @var CategoryRepository */
    private $categoryRepository;

    /**
     * Setter for $categoryRepository
     *
     * @param CategoryRepository $categoryRepository
     */
    public function setCategoryRepository($categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns a category by the slug
     *
     * @param string $categorySlug The category's slug
     *
     * @return Category
     */
    public function getCategoryBySlug($categorySlug)
    {
        $category = $this->categoryRepository->findBySlug($categorySlug);
    }
}
