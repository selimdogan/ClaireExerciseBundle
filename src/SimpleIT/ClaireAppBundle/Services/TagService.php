<?php
namespace SimpleIT\ClaireAppBundle\Services;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Tag;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\TagRepository;

/**
 * Class CategoryService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagService
{
    /** @var ClaireApi */
    private $claireApi;

    /** @var TagRepository */
    private $tagRepository;

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
     * Setter for $TagRepository
     *
     * @param TagRepositor $tagRepository
     */
    public function setTagRepository($tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Get a tag
     *
     * @param mixed $categoryIdentifier   The category id | slug*
     * @param mixed $tagIdentifier The tag id | slug
     *
     * @return Tag
     */
    public function getTag($categoryIdentifier, $tagIdentifier)
    {
        $tag = $this->tagRepository->find($categoryIdentifier, $tagIdentifier);

        return $tag;
    }

    /**
     * Get all tags
     *
     * @param mixed $categoryIdentifier   The category id | slug*
     * @param mixed $tagIdentifier The tag id | slug
     *
     * @return Tag
     */
    public function getTags(ApiRequestOptions $options)
    {
        $tags = $this->tagRepository->getAll($options);

        return $tags;
    }

    /**
     * Get a tag
     *
     * @param mixed $categoryIdentifier   The category id | slug*
     * @param mixed $tagIdentifier The tag id | slug
     *
     * @return Tag
     */
    public function getTagWithCourses($categoryIdentifier, $tagIdentifier, $options)
    {
        $tag = $this->tagRepository->findWithCourses($categoryIdentifier, $tagIdentifier, $options);
        $tag->setRecommendedCourses($this->tagRepository->findRecommendedCourses($tagIdentifier));

        return $tag;
    }

    /**
     * Get the recommended courses for the tag
     *
     * @param mixed $tagIdentifier Tag identifier (id | slug)
     *
     * @return array
     */
    public function getRecommendedCourses($tagIdentifier)
    {
        $recommendedCourses = $this->tagRepository->findRecommendedCourses($tagIdentifier);

        return $recommendedCourses;
    }
}
