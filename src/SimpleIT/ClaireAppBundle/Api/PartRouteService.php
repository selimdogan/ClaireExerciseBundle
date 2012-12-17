<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiRouteService;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Claire parts api
 */
class PartRouteService extends ApiRouteService
{
    const URL_COURSES = '/courses/';
    const URL_METADATAS = '/metadatas/';
    const URL_TAGS = '/tags/';

    /**
     * Get a course from slug
     *
     * @param string $slug Slug
     *
     * @return string Course at the html format
     */
    public function getPart($course, $part, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$course.'/part/'.$part);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get a course from slug
     *
     * @param string $slug Slug
     *
     * @return string Course at the html format
     */
    public function getIntroduction($course, $part, $apiRequestOptions = null)
    {
        if(is_null($apiRequestOptions))
        {
            $apiRequestOptions = new ApiRequestOptions();
        }

        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$course.'/part/'.$part.'/introduction');
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        $apiRequestOptions->setFormat('text/html');
        $apiRequestOptions = $this->bindOptions($apiRequestOptions);
        $apiRequest->setOptions($apiRequestOptions);

        return $apiRequest;
    }

    /**
     * Get metadatas for course from slug
     *
     * @param string $slug Slug
     */
    public function getPartMetadatas($course, $part, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl( self::URL_COURSES.$course.'/part/'.$part.self::URL_METADATAS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get tags for course from slug
     *
     * @param string $slug Slug
     */
    public function getPartTags($course, $part, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl( self::URL_COURSES.$course.'/part/'.$part.self::URL_TAGS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }
}
