<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Course\CourseGateway;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\AppBundle\Services\CacheServiceInterface;
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
     * @var CourseStatusRepository
     */
    private $courseStatusRepository;

    /**
     * @var CacheServiceInterface
     */
    private $cacheService;

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
     * @return CourseResource[]
     */
    public function findAllStatus($courseIdentifier)
    {
        return $this->courseStatusRepository->findAll($courseIdentifier);
    }

    /**
     * @return CourseResource
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function findPublished($courseIdentifier)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier)
        );
    }

    /**
     * @return CourseResource
     */
    public function findWaitingForPublication($courseIdentifier)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            array(CourseResource::STATUS => CourseResource::STATUS_WAITING_FOR_PUBLICATION)
        );
    }

    /**
     * @return CourseResource
     */
    public function findDraft($courseIdentifier)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
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

    public function updateToWaitingForPublication($courseId)
    {
        $this->client->send(
            $this->client->post(
                array(
                    $this->path . '/submit-to-publication',
                    array('courseIdentifier' => $courseId)
                )
            )
        );
    }

    /**
     * @CacheInvalidation(namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function updateWaitingForPublicationToPublished($courseId)
    {

        $this->client->send(
            $this->client->post(
                array(
                    $this->path . '/publish-waiting-for-publication',
                    array('courseIdentifier' => $courseId)
                )
            )
        );
        $this->invalidateCourseCache($courseId);
    }

    private function invalidateCourseCache($courseId)
    {
        $course = $this->find($courseId);
        $annotation = new CacheInvalidation();
        $annotation->namespacePrefix = 'claire_app_course_course';
        $annotation->namespaceAttributeValue = $course->getSlug();

        $this->cacheService->invalidateFromCacheInvalidationAnnotation($annotation);
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
     * @CacheInvalidation(namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier")
     */
    public function updateDraftToPublished($courseId)
    {

        $this->client->send(
            $this->client->post(
                array(
                    $this->path . '/publish-draft',
                    array('courseIdentifier' => $courseId)
                )
            )
        );
        $this->invalidateCourseCache($courseId);
    }

    public function updateDraft($courseId, CourseResource $course)
    {
        return $this->updateResource(
            $course,
            array('courseIdentifier' => $courseId,),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
    }

    public function setCourseStatusRepository(CourseStatusRepository $courseStatusRepository)
    {
        $this->courseStatusRepository = $courseStatusRepository;
    }

    public function setCacheService(CacheServiceInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }
}
