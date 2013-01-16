<?php
namespace SimpleIT\ClaireAppBundle\Repository\CourseAssociation;

use SimpleIT\AppBundle\Services\ApiService;
use SimpleIT\ClaireAppBundle\Model\CategoryFactory;
use SimpleIT\ClaireAppBundle\Model\CourseFactory;
use SimpleIT\ClaireAppBundle\Model\TagFactory;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiRouteService;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Class TagRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagRepository extends ApiRouteService
{
    /** @var ClaireApi The Claire Api */
    private $claireApi;

    /** URL for categories ressources */
    const URL_CATEGORIES = '/categories/';

    /* URL for tags ressources */
    const URL_TAGS = '/tags/';

    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses';

    /* URL for associated tags ressources */
    const URL_ASSOCIATED_TAGS = '/associated-tags/';

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
     * Get single tag from category and tag slug
     *
     * @param string $categoryIdentifier The requested category slug | id
     * @param string $tagIdentifier      The requested tag slug | id
     *
     * @return Tag
     */
    public function find($categoryIdentifier, $tagIdentifier)
    {
        $requests['tag'] = self::findRequest($categoryIdentifier, $tagIdentifier);
        $requests['category'] = CategoryRepository::findRequest($categoryIdentifier);

        $results = $this->claireApi->getResults($requests);

        ApiService::checkResponseSuccessful($results['tag']);
        ApiService::checkResponseSuccessful($results['category']);

        $tag = TagFactory::create($results['tag']->getContent());
        $category = CategoryFactory::create($results['category']->getContent());

        $tag->setCategory($category);

        return $tag;
    }

    /**
     * Get single tag from category and tag slug
     *
     * @param string $categoryIdentifier The requested category slug | id
     * @param string $tagIdentifier      The requested tag slug | id
     *
     * @return Tag
     */
    public function findWithCourses($categoryIdentifier, $tagIdentifier, ApiRequestOptions $apiRequestOptions)
    {
        $requests['tag'] = self::findRequest($categoryIdentifier, $tagIdentifier);
        $requests['category'] = CategoryRepository::findRequest($categoryIdentifier);
        $requests['courses'] = self::findAssociatedCourses($tagIdentifier, $apiRequestOptions);
        $requests['associatedTags'] = self::findAssociatedTags($categoryIdentifier, $tagIdentifier);

        $results = $this->claireApi->getResults($requests);

        ApiService::checkResponseSuccessful($results['tag']);
        ApiService::checkResponseSuccessful($results['category']);

        $tag = TagFactory::create($results['tag']->getContent());
        $category = CategoryFactory::create($results['category']->getContent());
        $tag->setCategory($category);

        if (ApiService::isResponseSuccessful($results['courses'])) {
            $courses = CourseFactory::createCollection(
                $results['courses']->getContent());
            
            $tag->setCourses($courses);
        }

        if (ApiService::isResponseSuccessful($results['associatedTags'])) {
            $associatedTags = TagFactory::createCollection(
                $results['associatedTags']->getContent());
            $tag->setAssociatedTags($associatedTags);
        }

        return $tag;
    }

    /**
     * Returns the tag (ApiRequest)
     *
     * @param mixed $categoryIdentifier The category identifier
     * @param mixed $tagIdentifier The tag identifier
     *
     * @return ApiRequest
     */
    public static function findRequest($categoryIdentifier, $tagIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_CATEGORIES.$categoryIdentifier.self::URL_TAGS.$tagIdentifier);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }


    /**
     * Returns the associated courses (ApiRequest)
     *
     * @param mixed             $tagIdentifier     The tag identifier
     * @param ApiRequestOptions $apiRequestOptions Api request options
     *
     * @return ApiRequest
     */
    public static function findAssociatedCourses($tagIdentifier, ApiRequestOptions $apiRequestOptions)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(
            self::URL_TAGS.$tagIdentifier.self::URL_COURSES
        );
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        if(!is_null($apiRequestOptions))
        {
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
    public static function findAssociatedTags($categoryIdentifier, $tagIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(
            self::URL_CATEGORIES.$categoryIdentifier.self::URL_TAGS.$tagIdentifier.self::URL_ASSOCIATED_TAGS
        );
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

}
