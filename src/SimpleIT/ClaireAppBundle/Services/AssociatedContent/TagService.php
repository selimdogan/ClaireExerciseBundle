<?php

namespace SimpleIT\ClaireAppBundle\Services\AssociatedContent;

use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByCourseRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagByPartRepository;
use SimpleIT\ClaireAppBundle\Repository\AssociatedContent\TagRepository;

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
