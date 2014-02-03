<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGateway;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class PartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartRepository extends AppRepository implements PartGateway
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * Find a part
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $parameters       Parameters
     *
     * @return PartResource
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function find(
        $courseIdentifier,
        $partIdentifier,
        Array $parameters = array()
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters
        );
    }

    /**
     * Find a part by status
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     * @param array  $parameters       Parameters
     *
     * @return PartResource
     */
    public function findByStatus(
        $courseIdentifier,
        $partIdentifier,
        Array $parameters = array()
    )
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            $parameters
        );
    }

    /**
     * Update a part
     *
     * @param string       $courseIdentifier Course id | slug
     * @param string       $partIdentifier   Part id | slug
     * @param PartResource $part             Part
     * @param array        $parameters       Parameters
     *
     * @return PartResource
     */
    public function update(
        $courseIdentifier,
        $partIdentifier,
        PartResource $part,
        $status
    )
    {
        return $this->updateResource(
            $part,
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier),
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * @return PartResource
     */
    public function findDraft($courseId, $partId)
    {
        return $this->findResource(
            array(
                'courseIdentifier' => $courseId,
                'partIdentifier'   => $partId
            ),
            array(CourseResource::STATUS => Status::DRAFT)
        );
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId, $partId)
    {
        return $this->findResource(
            array(
                'courseIdentifier' => $courseId,
                'partIdentifier'   => $partId
            ),
            array(CourseResource::STATUS => Status::WAITING_FOR_PUBLICATION)
        );
    }

    /**
     * @return PartResource
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function findPublished($courseIdentifier, $partIdentifier)
    {
        return $this->findResource(
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier
            ),
            array(CourseResource::STATUS => Status::PUBLISHED)
        );
    }

    public function updateDraft($courseId, $partId, PartResource $part)
    {
        return $this->updateResource(
            $part,
            array('courseIdentifier' => $courseId, 'partIdentifier' => $partId),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
    }

}
