<?php
namespace SimpleIT\ClaireAppBundle\Services;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Category;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;

/**
 * Class CategoryService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryService
{
    /** @var ClaireApi */
    private $claireApi;

    /** @var CategoryRepository */
    private $categoryRepository;

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi(ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

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
     * @param string $categoryIdentifier The category's identifier id | slug
     *
     * @return Category
     */
    public function getCategory($categoryIdentifier)
    {
        $category = $this->categoryRepository->find($categoryIdentifier);

        return $category;
    }

    /**
     * Returns all categories
     *
     * @param ApiRequestOptions $options The list options
     *
     * @return Collection
     */
    public function getCategories(ApiRequestOptions $options)
    {
        $categories = $this->categoryRepository->getAll($options);

        return $categories;
    }

    /**
     * Returns a complete category by the slug
     *
     * @param string $categoryIdentifier The category's identifier id | slug
     *
     * @return Category
     */
    public function getCategoryWithCourses($categoryIdentifier, ApiRequestOptions $options)
    {
        $category = $this->categoryRepository->findWithCourses($categoryIdentifier, $options);

        return $category;
    }
}
