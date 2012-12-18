<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiRouteService;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Claire courses api
 */
class CourseRouteService extends ApiRouteService
{
    const URL_COURSES = '/courses/';
    const URL_COURSES_TOC = '/toc';
    const URL_METADATAS = '/metadatas/';
    const URL_TAGS = '/tags/';

    /**
     * Get metadatas for course from slug
     *
     * @param string $slug Slug
     */
    public function getCourseMetadatas($slug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl( self::URL_COURSES.$slug.self::URL_METADATAS);
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
    public function getCourse($rootSlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$rootSlug);
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
    public function getIntroduction($rootSlug, $apiRequestOptions = null)
    {
        if(is_null($apiRequestOptions))
        {
            $apiRequestOptions = new ApiRequestOptions();
        }

        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$rootSlug.'/introduction');
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        $apiRequestOptions->setFormat('text/html');
        $apiRequestOptions = $this->bindOptions($apiRequestOptions);
        $apiRequest->setOptions($apiRequestOptions);

        return $apiRequest;
    }

    /**
     * Get a course from slug
     *
     * @param string $slug Slug
     *
     * @return string Course at the html format
     */
    public function getCourseByCategory($categorySlug, $rootSlug, $chapterSlug, $type, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl( CategoryRouteService::URL_CATEGORIES.$categorySlug.self::URL_COURSES.$rootSlug.((!empty($chapterSlug)) ? '/'.$type.'/'.$chapterSlug : ''));
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get tags for course
     *
     * @param string $rootSlug RootSlug
     *
     * @return string Course at the html format
     */
    public function getCourseTags($rootSlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$rootSlug.CategoryRouteService::URL_TAGS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get a toc from slug
     *
     * @param string $slug Slug
     *
     * @return string Toc at the html format
     */
    public function getCourseToc($slug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$slug.self::URL_COURSES_TOC);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get tags from category slug
     *
     * @param string $categorySlug Slug
     */
    public function getCourses($apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {

            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get tags from category slug
     *
     * @param string $categorySlug Slug
     */
    public function getCoursesByCategory($categorySlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(CategoryRouteService::URL_CATEGORIES.$categorySlug.self::URL_COURSES);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }
}
