<?php

namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\CategoryResource;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CategoryByCourseRepository;
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
     * @type CategoryRepository
     */
    private $categoryRepository;

    /**
     * @type CourseByCategoryRepository
     */
    private $courseByCategoryRepository;

    /**
     * @type TagByCategoryRepository
     */
    private $tagByCategoryRepository;

    /**
     * @type CategoryByCourseRepository
     */
    private $categoryByCourseRepository;

    /**
     * Get a list of categories
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation = null)
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
    public function getAllCourses(
        $categoryIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->courseByCategoryRepository->findAll(
            $categoryIdentifier,
            $collectionInformation
        );
    }

    /**
     * Get a list of tags of a category
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllTags(CollectionInformation $collectionInformation = null)
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

    /**
     * Get a category by course
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return CategoryResource
     */
    public function getByCourse($courseIdentifier)
    {
        return $this->categoryByCourseRepository->find($courseIdentifier);
    }

    /**
     * Add a course to a category
     *
     * @param int $categoryId Category id
     * @param int $courseId   Course id
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function addCourse($categoryId, $courseId)
    {
        return $this->courseByCategoryRepository->insert($categoryId, $courseId);
    }

    /**
     * Set categoryRepository
     *
     * @param CategoryRepository $categoryRepository
     */
    public function setCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Set courseByCategoryRepository
     *
     * @param CourseByCategoryRepository $courseByCategoryRepository
     */
    public function setCourseByCategoryRepository(
        CourseByCategoryRepository $courseByCategoryRepository
    )
    {
        $this->courseByCategoryRepository = $courseByCategoryRepository;
    }

    /**
     * Set categoryByCourseRepository
     *
     * @param CategoryByCourseRepository $categoryByCourseRepository
     */
    public function setCategoryByCourseRepository(
        CategoryByCourseRepository $categoryByCourseRepository
    )
    {
        $this->categoryByCourseRepository = $categoryByCourseRepository;
    }

    /**
     * Set tagByCategoryRepository
     *
     * @param TagByCategoryRepository $tagByCategoryRepository
     */
    public function setTagByCategoryRepository(TagByCategoryRepository $tagByCategoryRepository)
    {
        $this->tagByCategoryRepository = $tagByCategoryRepository;
    }
}
