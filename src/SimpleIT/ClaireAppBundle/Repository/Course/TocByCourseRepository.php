<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Gateways\Course\Toc\TocByCourseGateway;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TocByCourseRepository extends AppRepository implements TocByCourseGateway
{
    /**
     * @type string
     */
    protected $path = 'courses/{courseIdentifier}/toc';

    /**
     * @type string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * @return PartResource
     */
    public function findByStatus($courseId, $status)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => $status)
        );
    }

    /**
     * @return PartResource
     */
    public function update($courseId, PartResource $toc)
    {
        $data = $this->serializer->serialize($toc, 'json', array('edit'));
        $request = $this->client->put(
            array($this->path, self::formatIdentifiers(array('courseIdentifier' => $courseId))),
            null,
            $data
        );

        return $this->getSingleResource($request);
    }

    /**
     * @return CourseResource
     */
    public function findDraft($courseId)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => Status::DRAFT)
        );
    }

    /**
     * @return PartResource
     */
    public function findWaitingForPublication($courseId)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => Status::WAITING_FOR_PUBLICATION)
        );
    }

    /**
     * @return PartResource
     */
    public function findPublished($courseIdentifier)
    {
        return $this->findResource(array('courseIdentifier' => $courseIdentifier));
    }
}
