<?php

namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

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
     * @var  TagByPartRepository
     */
    private $tagByPartRepository;

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
     * Set tagByPartRepository
     *
     * @param TagByPartRepository $tagByPartRepository
     */
    public function setTagByPartRepository($tagByPartRepository)
    {
        $this->tagByPartRepository = $tagByPartRepository;
    }

    /**
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
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllByCourse($courseIdentifier)
    {
        return $this->tagByCourseRepository->findAll($courseIdentifier);
    }

    /**
     * Get all the tags of a part
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAllByPart($courseIdentifier, $partIdentifier)
    {
        return $this->tagByPartRepository->findAll($courseIdentifier, $partIdentifier);
    }
}
