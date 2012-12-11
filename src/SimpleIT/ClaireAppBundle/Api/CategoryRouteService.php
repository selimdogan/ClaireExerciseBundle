<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\AppBundle\Utils\ApiRequest;

/**
 * Claire categories api
 */
class CategoryRouteService
{
    /* URL for categories ressources */
    const URL_CATEGORIES = '/categories/';

    /* URL for tags ressources */
    const URL_TAGS = '/tags/';

    /* URL for associated tags ressources */
    const URL_ASSOCIATED_TAGS = '/associated-tags/';


    /**
     * Get a category from slug
     *
     * @param string $categorySlug The requested category slug
     * @param string $format       The requested format
     *
     * @return apiRequest
     */
    public function getCategory($categorySlug = '', $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setUrl(self::URL_CATEGORIES.$categorySlug);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat($format);

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
    public function getTags($categorySlug, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setUrl(self::URL_CATEGORIES.$categorySlug.self::URL_TAGS.'?sort=name asc');
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat($format);

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
    public function getTag($categorySlug, $tagSlug, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setUrl(self::URL_CATEGORIES.$categorySlug.self::URL_TAGS.$tagSlug);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat($format);

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
    public function getAssociatedTags($categorySlug, $tagSlug, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setUrl(
            self::URL_CATEGORIES.$categorySlug.self::URL_TAGS.$tagSlug.self::URL_ASSOCIATED_TAGS
        );
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat($format);

        return $apiRequest;
    }


}
