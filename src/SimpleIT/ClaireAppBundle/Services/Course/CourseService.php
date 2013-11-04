<?php

namespace SimpleIT\ClaireAppBundle\Services\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseContentRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseIntroductionRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseTocRepository;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\Utils\NumberUtils;

/**
 * Class CourseService
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseService
{
    private static $allowedTypes = array(
        1 => array('course', 'title-1'),
        2 => array('course', 'title-2', 'title-3')
    );

    /**
     * @type  CourseRepository
     */
    private $courseRepository;

    /**
     * @type CourseTocRepository
     */
    private $courseTocRepository;

    /**
     * @type CourseIntroductionRepository
     */
    private $courseIntroductionRepository;

    /**
     * @type CourseContentRepository
     */
    private $courseContentRepository;

    /* ****************** *
     *                    *
     * ***** COURSE ***** *
     *                    *
     * ****************** */

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
     * Get all courses for a courseIdentifier (all status)[status => $course]
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @throws \SimpleIT\CoreBundle\Exception\NonExistingObjectException
     * @return PaginatedCollection
     */
    public function getAllByCourseIdentifier($courseIdentifier)
    {

        $collectionInformation = new CollectionInformation();
        if (NumberUtils::isInteger($courseIdentifier)) {
            $collectionInformation->addFilter(CourseResource::ID, $courseIdentifier);
        } else {
            $collectionInformation->addFilter(CourseResource::SLUG, $courseIdentifier);
        }

        $collectionInformation->addFilter(
            CourseResource::STATUS,
            CourseResource::STATUS_DRAFT . ',' . CourseResource::STATUS_WAITING_FOR_PUBLICATION . ',' . CourseResource::STATUS_PUBLISHED
        );

        return $this->courseRepository->findAll($collectionInformation);
    }

    /**
     * Get a course
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function get($courseIdentifier)
    {
        return $this->courseRepository->find($courseIdentifier);
    }

    /**
     * Get a course where status is not published
     *
     * @param int    $courseId Course id
     * @param string $status   Status
     *
     * @deprecated use getByStatus
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function getToEdit($courseId, $status)
    {
        return $this->courseRepository->findByStatus(
            $courseId,
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * Get a course by status
     *
     * @param int    $courseId Course id
     * @param string $status   Status
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function getByStatus($courseId, $status)
    {
        return $this->courseRepository->findByStatus(
            $courseId,
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * Get course id
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return int
     */
    public function getId($courseIdentifier)
    {
        $courseId = $courseIdentifier;
        if (!CourseResource::isCourseId($courseIdentifier)) {
            $course = $this->get($courseIdentifier);
            $courseId = $course->getId();
        }

        return $courseId;
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

        $this->courseContentRepository->update(
            $course->getId(),
            '<h2>Titre 1</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ultricies massa sapien. Vivamus condimentum ante ac dolor accumsan laoreet. Ut facilisis lobortis turpis quis faucibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer ornare nibh at semper dapibus. Pellentesque risus libero, rhoncus ac lorem pulvinar, sollicitudin ultricies leo. Morbi mollis augue at urna aliquam tempus. Phasellus quis velit a nisi malesuada scelerisque. Nulla volutpat metus non ligula sagittis placerat. Sed semper erat dictum quam euismod egestas. Fusce mauris ligula, euismod id tempor at, bibendum eu lorem. Integer at nulla nec massa condimentum luctus id suscipit felis. Integer dignissim mollis eros ac vulputate. Nullam eleifend justo eu mauris aliquam accumsan. Quisque aliquam magna in commodo scelerisque. Quisque pharetra gravida elit sed venenatis.</p>'
        );

        return $course;
    }

    /**
     * Save a course
     *
     * @param int            $courseId Course id
     * @param CourseResource $course   Course
     * @param string         $status   Status
     *
     * @return \SimpleIT\ApiResourcesBundle\Course\CourseResource
     */
    public function save($courseId, CourseResource $course, $status)
    {
        return $this->courseRepository->update(
            $courseId,
            $course,
            array(CourseResource::STATUS => $status)
        );
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
     * @param int    $courseId      Course id
     * @param string $initialStatus Initial status
     *
     * @return CourseResource
     */
    private function setCourseStatusToWaitingForPublication(
        $courseId,
        $initialStatus
    )
    {
        $course = new CourseResource();
        $course->setStatus(CourseResource::STATUS_WAITING_FOR_PUBLICATION);
        $parameters = array(CourseResource::STATUS => $initialStatus);

        return $this->courseRepository->update($courseId, $course, $parameters);
    }

    /**
     * Set Course status to published
     *
     * @param int    $courseId      Course id
     * @param string $initialStatus Initial status
     *
     * @return CourseResource
     */
    private function setCourseStatusToPublished(
        $courseId,
        $initialStatus
    )
    {
        $course = new CourseResource();
        $course->setStatus(CourseResource::STATUS_PUBLISHED);
        $parameters = array(CourseResource::STATUS => $initialStatus);

        return $this->courseRepository->updateToPublished($courseId, $course, $parameters);
    }

    /* ******************* *
     *                     *
     * ***** CONTENT ***** *
     *                     *
     * ******************* */

    /**
     * Get a course content
     *
     * @param int|string $courseIdentifier Course id | slug
     *
     * @return array
     */
    public function getContent($courseIdentifier)
    {
        return $this->courseContentRepository->find($courseIdentifier);
    }

    /**
     * Get a course content
     *
     * @param int    $courseId Course id
     * @param string $status   Status
     *
     * @return array
     */
    public function getContentToEdit($courseId, $status)
    {
        return $this->courseContentRepository->findToEdit(
            $courseId,
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * Save a course content
     *
     * @param int    $courseId Course id
     * @param string $content  Course content
     * @param string $status   Status
     *
     * @return string
     */
    public function saveContent($courseId, $content, $status)
    {
        return $this->courseContentRepository->update(
            $courseId,
            $content,
            array(CourseResource::STATUS => $status)
        );
    }

    /* ************************ *
     *                          *
     * ***** INTRODUCTION ***** *
     *                          *
     * ************************ */

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
     * Get a course introduction to edit
     *
     * @param int    $courseId Course id
     * @param string $status   Status
     *
     * @return string
     */
    public function getIntroductionToEdit($courseId, $status)
    {
        return $this->courseIntroductionRepository->findToEdit(
            $courseId,
            array(CourseResource::STATUS => $status)
        );
    }

    /* *************** *
     *                 *
     * ***** TOC ***** *
     *                 *
     * *************** */

    /**
     * Get a course table of content
     *
     * @param int | string $courseIdentifier Course id | slug
     *
     * @return CourseResource
     */
    public function getToc($courseIdentifier)
    {
        return $this->courseTocRepository->find($courseIdentifier);
    }

    /**
     * Get a course table of content
     *
     * @param int|string $courseIdentifier Course id | slug
     * @param string     $status           Status
     *
     * @return CourseResource
     */
    public function getTocToEdit($courseIdentifier, $status)
    {
        return $this->courseTocRepository->findToEdit(
            $courseIdentifier,
            array(CourseResource::STATUS => $status)
        );
    }

    /* ********************** *
     *                        *
     * ***** PAGINATION ***** *
     *                        *
     * ********************** */

    /**
     * Get a course or a part previous page and next page
     *
     * @param int|string $courseIdentifier Course id | slug
     * @param int|string $identifier       Part id | slug
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
     * Get a course or a part previous page and next page
     *
     * @param int    $courseId Course id
     * @param string $status   Status
     * @param int    $partId   Part id
     *
     * @return array
     */
    public function getPaginationToEdit($courseId, $status, $partId = null)
    {
        $course = $this->getToEdit($courseId, $status);
        $toc = $this->getTocToEdit($courseId, $status);

        if (is_null($partId)) {
            $pagination = $this->buildPagination(
                $toc,
                $courseId,
                $course->getDisplayLevel()
            );
        } else {
            $pagination = $this->buildPagination($toc, $partId, $course->getDisplayLevel());
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
        $list = array();
        $pagination = array('previous' => null, 'next' => null);
        $previous = null;

        /* Linearies the tree in $list */
        $stack->push($part);
        do {
            $current = $stack->pop();

            if (is_array($current->getChildren())) {
                foreach (array_reverse($current->getChildren()) as $child) {
                    $stack->push($child);
                }
            }

            $list[] = $current;
        } while (!$stack->isEmpty());

        for ($i = 0; $i < count($list); $i++) {
            /* Find the current node */
            /** @type array<PartResource> $list */
            if ($identifier == $list[$i]->getId() || $identifier == $list[$i]->getSlug()) {
                $pagination['previous'] = $previous;

                /* Find the next acceptable node */
                for ($k = $i + 1; $k < count($list); $k++) {
                    if (in_array($list[$k]->getSubtype(), self::$allowedTypes[$displayLevel])) {
                        $pagination['next'] = $list[$k];
                        break;
                    }
                }
                break;
            }

            /* Memorize previous acceptable node */
            if (in_array($list[$i]->getSubtype(), self::$allowedTypes[$displayLevel])) {
                $previous = $list[$i];
            }
        }

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
}
