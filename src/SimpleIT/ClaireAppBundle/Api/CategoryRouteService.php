<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiRouteService;

/**
 * Claire categories api
 */
class CategoryRouteService extends ApiRouteService
{
    /* URL for categories ressources */
    const URL_CATEGORIES = '/categories/';

    /* URL for tags ressources */
    const URL_TAGS = '/tags/';

    /* URL for associated tags ressources */
    const URL_ASSOCIATED_TAGS = '/associated-tags/';

    /* URL for related courses */
    const URL_COURSES = '/courses';


    public function getItemsPerPageDefault()
    {
        return self::ITEM_PER_PAGE_DEFAULT;
    }
    /**
     * Get a category from slug
     *
     * @param string $categorySlug The requested category slug
     * @param string $format       The requested format
     *
     * @return apiRequest
     */
    public function getCategory($categorySlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_CATEGORIES.$categorySlug);
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
     * @param string $categorySlug The requested category slug
     * @param string $format       The requested format
     *
     * @return apiRequest
     */
    public function getTags($categorySlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_CATEGORIES.$categorySlug.self::URL_TAGS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get single tag from category and tag slug
     *
     * @param string $categorySlug The requested category slug
     * @param string $tagSlug      The requested tag slug
     * @param string $format       The requested format
     *
     * @return apiRequest
     */
    public function getTag($categorySlug, $tagSlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_CATEGORIES.$categorySlug.self::URL_TAGS.$tagSlug);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get associated tags for this tag
     *
     * @param string $categorySlug The requested category slug
     * @param string $tagSlug      The requested tag slug
     * @param string $format       The requested format
     *
     * @return apiRequest
     */
    public function getAssociatedTags($categorySlug, $tagSlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(
            self::URL_CATEGORIES.$categorySlug.self::URL_TAGS.$tagSlug.self::URL_ASSOCIATED_TAGS
        );
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }

    /**
     * Get associated tags for this tag
     *
     * @param string $categorySlug The requested category slug
     * @param string $tagSlug      The requested tag slug
     * @param string $format       The requested format
     *
     * @return apiRequest
     */
    public function getTagCourses($tagSlug, $apiRequestOptions = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(
            self::URL_TAGS.$tagSlug.CategoryRouteService::URL_COURSES
        );
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequestOptions = $this->bindOptions($apiRequestOptions);
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }
}
