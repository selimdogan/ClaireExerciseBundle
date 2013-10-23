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
     * Get a course
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param array        $parameters       Parameters
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function get($courseIdentifier, array $parameters = array())
    {
        return $this->courseRepository->find($courseIdentifier, $parameters);
    }

    /**
     * Get a course to edit
     *
     * @param int | string $courseId   Course id
     * @param string       $status     Status
     * @param array        $parameters Parameters
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function getCourseToEdit($courseId, $status, array $parameters = array())
    {
        $parameters[CourseResource::STATUS] = $status;

        return $this->courseRepository->findToEdit($courseId, $parameters);
    }

    /**
     * Add a course
     *
     * @return CourseResource
     */
    public function add()
    {
        $course = new CourseResource();
        $course->setDisplayLevel(CourseResource::DISPLAY_LEVEL_SMALL);
        $course->setTitle('Sans titre');
        $course = $this->courseRepository->insert($course);

        $this->courseContentRepository->update($course->getId(), '<h2>Test</h2>');

        return $course;
    }

    /**
     * Save a course
     *
     * @param int | string   $courseIdentifier Course id | slug
     * @param CourseResource $course           Course
     * @param array          $parameters       Parameters
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function save($courseIdentifier, CourseResource $course, array $parameters = array())
    {
        return $this->courseRepository->update($courseIdentifier, $course, $parameters);
    }

    /**
     * Change status of a course
     *
     * @param int    $courseId      Course id
     * @param string $initialStatus The initial status
     * @param string $finalStatus   The status to be
     *
     * @throws \InvalidArgumentException
     * @return CourseResource
     */
    public function changeStatus($courseId, $initialStatus, $finalStatus)
    {
        $course = null;
        switch ($finalStatus) {
            case CourseResource::STATUS_WAITING_FOR_PUBLICATION :
                $course = $this->setCourseStatusToWaitingforPublication(
                    $courseId,
                    $initialStatus
                );
                break;
            case CourseResource::STATUS_PUBLISHED :
                $course = $this->setCourseStatusToPublished(
                    $courseId,
                    $initialStatus
                );
                break;
            default:
                throw new \InvalidArgumentException();
                break;
        }

        return $course;
    }

    /**
     * Set Course status to waiting for publication
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param string       $initialStatus    Initial status
     *
     * @return CourseResource
     */
    private function setCourseStatusToWaitingForPublication(
        $courseIdentifier,
        $initialStatus
    )
    {
        $course = new CourseResource();
        $course->setStatus(CourseResource::STATUS_WAITING_FOR_PUBLICATION);
        $parameters = array(CourseResource::STATUS => $initialStatus);

        return $this->courseRepository->update($courseIdentifier, $course, $parameters);
    }

    /**
     * Set Course status to published
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param string       $initialStatus    Initial status
     *
     * @return CourseResource
     */
    private function setCourseStatusToPublished(
        $courseIdentifier,
        $initialStatus
    )
    {
        $course = new CourseResource();
        $course->setStatus(CourseResource::STATUS_PUBLISHED);
        $parameters = array(CourseResource::STATUS => $initialStatus);

        return $this->courseRepository->update($courseIdentifier, $course, $parameters);
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
     * @param string       $status           Status
     *
     * @return array
     */
    public function getContent($courseIdentifier, $status)
    {
        return $this->courseContentRepository->find($courseIdentifier, array('status' => $status));
    }

    /**
     * Get a course introduction
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return array
     */
    public function getContentToEdit($courseIdentifier)
    {
        return $this->courseContentRepository->find($courseIdentifier, array('status' => CourseResource::STATUS_DRAFT));
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
}
