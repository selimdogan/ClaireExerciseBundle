<?php

/**
 * This file is part of the Claire App Package
 *
 * PHP version 5
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */

namespace SimpleIT\ClaireAppBundle\Repository\CourseAssociation;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiRouteService;
use SimpleIT\ClaireAppBundle\Repository\Course\CourseRepository;
use SimpleIT\ClaireAppBundle\Repository\Course\PartRepository;

/**
 * Description of AssociatedCourseRepository
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */
class AssociatedCourseRepository extends ApiRouteService
{
    /** The url for associated courses = '/associatedCourses' */
    const URL_COURSES_ASSOCIATED = '/associatedCourses';

    /** @var ClaireApi The Claire Api */
    private $claireApi;

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi The CLAIRE Api
     *
     * @return ClaireApi
     */
    public function setClaireApi (ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /**
     * Find associated courses for course or part identifier
     *
     * @param string|integer $courseIdentifier The Course identifier
     *      <ul>
     *          <li>id : the course id (integer)</li>
     *          <li>slug : the course slug (string)</li>
     *      </ul>
     * @param string|integer $partIdentifier   The Part identifier
     *      <ul>
     *          <li>id : the part id (integer)</li>
     *          <li>slug : the part slug (string)</li>
     *      </ul>
     *
     * @return array
     */
    public function findAssociatedCourse($courseIdentifier, $partIdentifier = null)
    {
        $requests['associatedCourses'] = $this->findAssociatedCoursesRequest(
            $courseIdentifier,
            $partIdentifier
        );
        $apiPartResults = $this->claireApi->getResults($requests);
        $associatedCourses = $apiPartResults['associatedCourses']->getContent();

        if (!is_array($associatedCourses)) {
            $associatedCourses = array();
        }

        return $associatedCourses;
    }

    /**
     * Returns associated courses for a given part or course (ApiRequest)
     *
     * @param string|integer $courseIdentifier The Course identifier
     *      <ul>
     *          <li>id : the course id (integer)</li>
     *          <li>slug : the course slug (string)</li>
     *      </ul>
     * @param string|integer $partIdentifier   The Part identifier
     *      <ul>
     *          <li>id : the part id (integer)</li>
     *          <li>slug : the part slug (string)</li>
     *      </ul>
     *
     * @return ApiRequest
     */
    public static function findAssociatedCoursesRequest(
        $courseIdentifier,
        $partIdentifier = null
    ) {
        $apiRequest = new ApiRequest();

        $baseUrl = CourseRepository::URL_COURSES.$courseIdentifier;
        if (!\is_null($partIdentifier)) {
            $baseUrl .= PartRepository::URL_PART.$partIdentifier;
        }
        $baseUrl .= self::URL_COURSES_ASSOCIATED;

        $apiRequest->setBaseUrl($baseUrl);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }
}

