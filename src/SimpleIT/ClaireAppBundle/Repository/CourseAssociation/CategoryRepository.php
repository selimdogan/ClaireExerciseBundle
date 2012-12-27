<?php
namespace SimpleIT\ClaireAppBundle\Repository\CourseAssociation;

use SimpleIT\ClaireAppBundle\Model\CategoryFactory;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;

use SimpleIT\AppBundle\Model\ApiRequest;

use SimpleIT\AppBundle\Services\ApiRouteService;

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
    public function find($categoryIdentifier)
    {
        $categoryRequest = self::findRequest($categoryIdentifier);

        $categoryResult = $this->claireApi->getResult($categoryRequest);
        $category = CategoryFactory::create($categoryResult->getContent());

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
}
