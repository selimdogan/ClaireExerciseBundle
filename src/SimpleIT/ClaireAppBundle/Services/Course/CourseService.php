<?php


namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseRepository;

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
     * Get a course
     *
     * @param int | string $courseIdentifier Course id | slug
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
     */
    public function save($courseIdentifier, CourseResource $course)
    {
        return $this->courseRepository->update($course);
    }
}
