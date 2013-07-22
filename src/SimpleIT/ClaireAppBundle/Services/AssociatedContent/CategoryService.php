<?php


namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CategoryRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CourseByCategoryRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByCategoryRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

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
     * @var CourseByCategoryRepository
     */
    private $courseByCategoryRepository;

    /**
     * @var  TagByCategoryRepository
     */
    private $tagByCategoryRepository;

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
     * Set courseByCategoryRepository
     *
     * @param CourseByCategoryRepository $courseByCategoryRepository
     */
    public function setCourseByCategoryRepository($courseByCategoryRepository)
    {
        $this->courseByCategoryRepository = $courseByCategoryRepository;
    }

    /**
     * Set tagByCategoryRepository
     *
     * @param TagByCategoryRepository $tagByCategoryRepository
     */
    public function setTagByCategoryRepository($tagByCategoryRepository)
    {
        $this->tagByCategoryRepository = $tagByCategoryRepository;
    }

    /**
     * Get a list of categories
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation)
    {
        return $this->categoryRepository->findAll($collectionInformation);
    }

    /**
     * Get a list of courses of a category
     *
     * @param int |string           $categoryIdentifier    Category id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllCourses($categoryIdentifier, CollectionInformation $collectionInformation = null)
    {
        return $this->courseByCategoryRepository->findAll($categoryIdentifier, $collectionInformation);
    }

    /**
     * Get a list of tags of a category
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllTags(CollectionInformation $collectionInformation)
    {
        return $this->tagByCategoryRepository->findAll($collectionInformation);
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
