<?php

namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
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
    private static $allowedTypes = array(
        1 => array('course', 'title-1', 'title-2'),
        2 => array('course', 'title-2', 'title-3')
    );

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
     * Set courseContentRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Course\CourseContentRepository $courseContentRepository
     */
    public function setCourseContentRepository($courseContentRepository)
    {
        $this->courseContentRepository = $courseContentRepository;
    }

    /**
     * Get all courses
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function getAll(CollectionInformation $collectionInformation = null)
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
     * @param int | string $courseIdentifier                 Course id | slug
     * @param CourseResource $course                         Course
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
     * @return array
     */
    public function getContent($courseIdentifier)
    {
        return $this->courseContentRepository->find($courseIdentifier);
    }

    /**
     * Get a course or a part previous page and next page
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $identifier       Part id | slug
     *
     * @return array
     */
    public function getPagination($courseIdentifier, $identifier = null)
    {
        $course = $this->get($courseIdentifier);
        $toc = $this->getToc($courseIdentifier);

        if (is_null($identifier)) {
            $pagination = $this->buildPagination(
                $toc,
                $courseIdentifier,
                $course->getDisplayLevel()
            );
        } else {
            $pagination = $this->buildPagination($toc, $identifier, $course->getDisplayLevel());
        }

        return $pagination;
    }

    /**
     * @param PartResource $part         Part
     * @param int | string $identifier   Current element id | slug
     * @param int          $displayLevel Display level
     *
     * @return array
     */
    private function buildPagination(
        PartResource $part,
        $identifier,
        $displayLevel
    )
    {
        $stack = new \SplStack();
        $pagination = array('previous' => null, 'next' => null);
        $previous = null;

        $stack->push($part);

        do {
            $current = $stack->pop();

            if (is_array($current->getChildren())) {
                foreach (array_reverse($current->getChildren()) as $child) {
                    $stack->push($child);
                }
            }

            if ($identifier == $current->getId() || $identifier == $current->getSlug()) {
                $pagination['previous'] = $previous;
                $pagination['next'] = $stack->pop();
                break;
            }

            if (in_array($current->getSubtype(), self::$allowedTypes[$displayLevel])) {
                $previous = $current;
            }

        } while (!$stack->isEmpty());

        return $pagination;
    }
}
