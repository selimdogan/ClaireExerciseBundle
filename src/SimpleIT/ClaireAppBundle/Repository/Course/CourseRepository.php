<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\ClaireAppBundle\Gateways\Course\Course\CourseGateway;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;
use SimpleIT\AppBundle\Annotation\CacheInvalidation;

/**
 * Class CourseRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseRepository extends AppRepository implements CourseGateway
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\CourseResource';

    /**
     * Find a list of courses
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(array(), $collectionInformation);
    }

    /**
     * Find a course
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     *
     * @return CourseResource
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function find($courseIdentifier, array $parameters = array())
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters
        );
    }

    /**
     * Find a course to edit
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     *
     * @return CourseResource
     */
    public function findByStatus($courseIdentifier, array $parameters = array())
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters
        );
    }

    /**
     * Insert a course
     *
     * @param CourseResource $course Course
     *
     * @return CourseResource
     */
    public function insert(CourseResource $course)
    {
        return $this->insertResource($course);
    }

    /**
     * Update a course
     *
     * @param string         $courseIdentifier Course id | slug
     * @param CourseResource $course           Course
     * @param array          $parameters       Parameters
     *
     * @return CourseResource
     */
    public function update($courseIdentifier, CourseResource $course, array $parameters = array())
    {
        return $this->updateResource(
            $course,
            array('courseIdentifier' => $courseIdentifier,),
            $parameters
        );
    }

    /**
     * @CacheInvalidation(namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function updateToWaitingForPublication($courseId)
    {
        $course = new CourseResource();
        $course->setStatus(CourseResource::STATUS_WAITING_FOR_PUBLICATION);

        return $this->updateResource(
            $course,
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
    }

    /**
     * @CacheInvalidation(namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function updateToPublished($courseId)
    {
        $course = new CourseResource();
        $course->setStatus(CourseResource::STATUS_PUBLISHED);

        return $this->updateResource(
            $course,
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_WAITING_FOR_PUBLICATION)

        );
    }
}
