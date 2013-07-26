<?php

namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseContentRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseIntroductionRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseTocRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class CourseService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseService
{
    /**
     * @var  CourseRepository
     */
    private $courseRepository;

    /**
     * @var  CourseTocRepository
     */
    private $courseTocRepository;

    /**
     * @var  CourseIntroductionRepository
     */
    private $courseIntroductionRepository;

    /**
     * @var  CourseContentRepository
     */
    private $courseContentRepository;

    /**
     * Set courseIntroductionRepository
     *
     * @param CourseIntroductionRepository $courseIntroductionRepository
     */
    public function setCourseIntroductionRepository($courseIntroductionRepository)
    {
        $this->courseIntroductionRepository = $courseIntroductionRepository;
    }

    /**
     * Set courseRepository
     *
     * @param CourseRepository $courseRepository
     */
    public function setCourseRepository($courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Set courseTocRepository
     *
     * @param CourseTocRepository $courseTocRepository
     */
    public function setCourseTocRepository($courseTocRepository)
    {
        $this->courseTocRepository = $courseTocRepository;
    }

    /**
     * Get all courses
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation)
    {
        return $this->courseRepository->findAll($collectionInformation);
    }

    /**
     * Get a course
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function get($courseIdentifier)
    {
        return $this->courseRepository->find($courseIdentifier);
    }

    /**
     * Save a course
     *
     * @param int | string   $courseIdentifier Course id | slug
     * @param CourseResource $course           Course
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function save($courseIdentifier, CourseResource $course)
    {
        return $this->courseRepository->update($courseIdentifier, $course);
    }

    /**
     * Get a course table of content
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return mixed
     */
    public function getToc($courseIdentifier)
    {
        return $this->courseTocRepository->find($courseIdentifier);
    }

    /**
     * Get a course introduction
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return mixed
     */
    public function getIntroduction($courseIdentifier)
    {
        return $this->courseIntroductionRepository->find($courseIdentifier);
    }

    /**
     * Get a course introduction
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return mixed
     */
    public function getContent($courseIdentifier)
    {
        return $this->courseContentRepository->find($courseIdentifier);
    }
}
