<?php
namespace SimpleIT\ClaireAppBundle\Repository\CourseAssociation;

use SimpleIT\AppBundle\Services\ApiService;
use SimpleIT\ClaireAppBundle\Model\CategoryFactory;
use SimpleIT\ClaireAppBundle\Model\TagFactory;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiRouteService;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Class CourseRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryRepository extends ApiRouteService
{
    /** @var ClaireApi The Claire Api */
    private $claireApi;

    /** URL for categories ressources */
    const URL_CATEGORIES = '/categories/';

    /* URL for tags ressources */
    const URL_TAGS = '/tags/';

    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses/';

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi (ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /**
     * Returns a category
     *
     * @param mixed $categoryIdentifier The category identifier
     *
     * @return Category The category
     */
    public function findWithCourses($categoryIdentifier, ApiRequestOptions $apiRequestOptions)
    {
        $requests['category'] = self::findRequest($categoryIdentifier);
        $requests['tags'] = self::findTags($categoryIdentifier);
        $requests['courses'] = self::findCourses($categoryIdentifier, $apiRequestOptions);

        $results = $this->claireApi->getResults($requests);

        ApiService::checkResponseSuccessful($results['category']);

        $category = CategoryFactory::create($results['category']->getContent());

        if (ApiService::isResponseSuccessful($results['tags'])) {
           /* $tags = TagFactory::createCollection(
                $results['tags']->getContent());
            $category->setTags($tags);*/

            $category->setTags($results['tags']->getContent());
        }

        if (ApiService::isResponseSuccessful($results['courses'])) {
            $category->setCourses($results['courses']->getContent());
        }

        return $category;
    }

    /**
     * Returns a category
     *
     * @param mixed $categoryIdentifier The category identifier
     *
     * @return Category The category
     */
    public function find($categoryIdentifier)
    {
        $requests['category'] = self::findRequest($categoryIdentifier);

        $results = $this->claireApi->getResults($requests);

        ApiService::checkResponseSuccessful($results['category']);

        $category = CategoryFactory::create($results['category']->getContent());

        return $category;
    }

    /**
     * Returns the category (ApiRequest)
     *
     * @param mixed $categoryIdentifier The category identifier
     *
     * @return ApiRequest
     */
    public static function findRequest($categoryIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_CATEGORIES.$categoryIdentifier);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns the category (ApiRequest)
     *
     * @param mixed $categoryIdentifier The category identifier
     *
     * @return ApiRequest
     */
    public static function findTags($categoryIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_CATEGORIES.$categoryIdentifier.self::URL_TAGS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns the category courses
     *
     * @param mixed $categoryIdentifier The category identifier
     *
     * @return ApiRequest
     */
    public static function findCourses($categoryIdentifier, ApiRequestOptions $apiRequestOptions)
    {
        /* @FIXME USE /categories/{identifier}/courses */
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
            $apiRequest->setOptions($apiRequestOptions);
        }

        return $apiRequest;
    }
}
