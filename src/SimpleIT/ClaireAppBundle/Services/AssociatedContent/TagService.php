<?php

namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CourseByTagRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\RecommendedCourseByTagRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByCategoryRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByCourseRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByPartRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class TagService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagService
{
    /**
     * @var  TagRepository
     */
    private $tagRepository;

    /**
     * @var TagByCategoryRepository
     */
    private $tagByCategoryRepository;

    /**
     * @var  TagByCourseRepository
     */
    private $tagByCourseRepository;

    /**
     * @var CourseByTagRepository
     */
    private $courseByTagRepository;

    /**
     * @var RecommendedCourseByTagRepository
     */
    private $recommendedCourseByTagRepository;

    /**
     * @var  TagByPartRepository
     */
    private $tagByPartRepository;

    /**
     * @var  PartService
     */
    private $partService;

    /**
     * Get a list of tags
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->tagRepository->findAll($collectionInformation);
    }

    /**
     * Get a list of tag of a category
     *
     * @param int | string          $categoryIdentifier    Category id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllByCategory(
        $categoryIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->tagByCategoryRepository->findAll($categoryIdentifier, $collectionInformation);
    }

    /**
     * Get all the tags of a course
     *
     * @param int | string          $courseIdentifier      Course id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllByCourse(
        $courseIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->tagByCourseRepository->findAll($courseIdentifier, $collectionInformation);
    }

    /**
     * Get all the tags of a part
     *
     * @param int | string          $courseIdentifier      Course id | slug
     * @param int | string          $partIdentifier        Part id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllByPart(
        $courseIdentifier,
        $partIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        /* check parents */
        $parents = $this->partService->getParents($courseIdentifier, $partIdentifier);
        $tags = null;
        while (count($parents) > 0 && is_null($tags)) {
            $parentPartIdentifier = array_shift($parents);
            $tags = $this->tagByPartRepository->findAll(
                $courseIdentifier,
                $parentPartIdentifier,
                $collectionInformation
            );
        }

        /* finally get course tags */
        if (is_null($tags) == 0) {
            $tags = $this->getAllByCourse($courseIdentifier, $collectionInformation);
        }

        return $tags;
    }

    /**
     * Return recommended courses
     *
     * @param int | string          $tagIdentifier         Tag id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getRecommendedCourses(
        $tagIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->recommendedCourseByTagRepository->findAll(
            $tagIdentifier,
            $collectionInformation
        );
    }

    /**
     * Get courses of a tag
     *
     * @param int | string          $tagIdentifier         Tag id | slug
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllCourses(
        $tagIdentifier,
        CollectionInformation $collectionInformation = null
    )
    {
        return $this->courseByTagRepository->findAll(
            $tagIdentifier,
            $collectionInformation
        );
    }

    /**
     * Get a tag
     *
     * @param int | string $tagIdentifier Tag id | slug
     *
     * @return TagResource
     */
    public function get($tagIdentifier)
    {
        return $this->tagRepository->find($tagIdentifier);
    }

    /**
     * Set tagRepository
     *
     * @param TagRepository $tagRepository
     */
    public function setTagRepository($tagRepository)
    {
        $this->tagRepository = $tagRepository;
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
     * Set tagByCourseRepository
     *
     * @param TagByCourseRepository $tagByCourseRepository
     */
    public function setTagByCourseRepository($tagByCourseRepository)
    {
        $this->tagByCourseRepository = $tagByCourseRepository;
    }

    /**
     * Set recommendedCourseByTagRepository
     *
     * @param RecommendedCourseByTagRepository $recommendedCourseByTagRepository
     */
    public function setRecommendedCourseByTagRepository($recommendedCourseByTagRepository)
    {
        $this->recommendedCourseByTagRepository = $recommendedCourseByTagRepository;
    }

    /**
     * Set courseByTagRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\AssociatedContent\CourseByTagRepository $courseByTagRepository
     */
    public function setCourseByTagRepository($courseByTagRepository)
    {
        $this->courseByTagRepository = $courseByTagRepository;
    }

    /**
     * Set tagByPartRepository
     *
     * @param TagByPartRepository $tagByPartRepository
     */
    public function setTagByPartRepository($tagByPartRepository)
    {
        $this->tagByPartRepository = $tagByPartRepository;
    }

    /**
     * Set partService
     *
     * @param PartService $partService
     */
    public function setPartService($partService)
    {
        $this->partService = $partService;
    }
}
