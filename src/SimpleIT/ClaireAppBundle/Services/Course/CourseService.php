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
     * @param int | string   $courseIdentifier               Course id | slug
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
                $course->getDisplayLevel(),
                $toc
            );
            $pagination['previous'] = null;
        } else {
            $pagination = $this->buildPagination($toc, $identifier, $course->getDisplayLevel());
        }

        return $pagination;

    }

    /**
     * @param PartResource $part         Part
     * @param int | string $identifier   Current element id | slug
     * @param int          $displayLevel Display level
     * @param PartResource $previous     Previous part
     * @param array        $pagination   Pagination
     *
     * @return array
     */
    private function buildPagination(
        PartResource $part,
        $identifier,
        $displayLevel,
        $previous = null,
        $pagination = array('previous' => null, 'next' => null)

    )
    {
        if (!is_null($pagination['previous'])
            && in_array($part->getSubtype(), self::$allowedTypes[$displayLevel])
        ) {
            $pagination['next'] = $part;
        }
        if ($identifier == $part->getId() || $identifier == $part->getSlug()) {
            $pagination['previous'] = $previous;
        } else {
            if (in_array($part->getSubtype(), self::$allowedTypes[$displayLevel])) {
                $previous = $part;
            }
        }

        $children = $part->getChildren();
        if (is_array($children)) {

            for ($i = 0; $i < sizeof($children) && is_null($pagination['next']); $i++) {
                $pagination = $this->buildPagination(
                    $children[$i],
                    $identifier,
                    $displayLevel,
                    $previous,
                    $pagination
                );
            }
        }

        return $pagination;
    }
}
