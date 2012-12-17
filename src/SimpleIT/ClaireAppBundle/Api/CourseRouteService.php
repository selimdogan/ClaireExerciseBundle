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
    const URL_METADATAS = '/metadatas';
    const URL_TAGS = '/tags/';

    /**
     * Get timeline for course from slug
     *
     * @param string $slug Slug
     */
    public function getCourseTimeline($slug, $apiRequestOptions = null)
    {
        if(is_null($apiRequestOptions))
        {
            $apiRequestOptions = new ApiRequestOptions();
        }

        $apiRequestOptions->bindFilter(array('level' => 3, 'type' => 'title-1+title-2+title-3'));

        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl( self::URL_COURSES.$slug.'/toc');
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

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
    public function getCourse($rootSlug, $chapterSlug, $type, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl( self::URL_COURSES.$rootSlug.((!empty($chapterSlug)) ? '/'.$type.'/'.$chapterSlug : ''));
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
    public function getIntroduction($rootSlug, $chapterSlug, $type, $apiRequestOptions = null)
    {
        if(is_null($apiRequestOptions))
        {
            $apiRequestOptions = new ApiRequestOptions();
        }

        $apiRequestOptions->setFormat('text/html');
        $apiRequest = $this->getCourse($rootSlug, $chapterSlug, $type, $apiRequestOptions);
        $apiRequest->setBaseUrl(self::URL_COURSES.$rootSlug.((!empty($chapterSlug)) ? '/'.$type.'/'.$chapterSlug : '').'/introduction');

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
     * Get a course from slug
     *
     * @param string $slug Slug
     *
     * @return string Course at the html format
     */
    public function getCourseContent($rootSlug, $chapterSlug, $type, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$rootSlug.((!empty($chapterSlug)) ? '/'.$type.'/'.$chapterSlug : ''));
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat(\SimpleIT\AppBundle\Model\ApiRequest::FORMAT_HTML);

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
        if(is_null($apiRequestOptions))
        {
            $apiRequestOptions = new ApiRequestOptions();
        }

        $apiRequestOptions->bindFilter(array('level' => 3, 'type' => 'title-1+title-2+title-3'));

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

    /**
     * Create a new course
     *
     * @param array $course Data of the course to create
     *
     * @return array Data of the course
     */
    public function createCourse($course)
    {
        $course = $this->getTransportService()->post(self::elements, array('Accept' => 'application/json'), $course)->getContent();

        $course = json_decode($course, true);
        $data = array('rootElement' => $course['id'], 'reference' => $course['reference']['id']);

        /* Create branch */
        $this->getTransportService()->post(self::courses, array('Accept' => 'application/json'), $data);

        return $course;
    }

    /**
     * Update a course
     *
     * @param array $course Data of the course to update
     *
     * @return array Data of the course
     */
    public function updateCourse($course)
    {
        $branches = $this->getTransportService()->get(self::courses.'?reference='.$course['reference']['slug'])->getContent();
        $branches = json_decode($branches, true);
        $rootElement = $branches['branches'][0]['rootElement'];
        $branch = $branches['branches'][0];

        $data = array('rootElement' => $rootElement['id'], 'branch' => $branch['id'], 'content' => $course['content']);
        $course = $this->getTransportService()->put(self::elements.$course['id'], array('Accept' => 'application/json'), http_build_query($data))->getContent();
        $course = json_decode($course, true);

        return $course;
    }
}
