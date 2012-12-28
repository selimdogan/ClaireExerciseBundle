<?php

namespace SimpleIT\ClaireAppBundle\Repository\User;

use SimpleIT\ClaireAppBundle\Model\AuthorFactory;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Services\ApiRouteService;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiService;

/**
 * Description of AuthorRepository
 *
 * @author Isabelle Bruchet <isabelle.bruchet@simple-it.fr>
 */
class AuthorRepository extends ApiRouteService
{
    /** @var ClaireApi The Claire Api */
    private $claireApi;

    /** URL for authors ressources */
    const URL_AUTHORS = '/authors/';

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
     * Returns an author
     *
     * @param mixed $authorIdentifier The author identifier
     *
     * @return Author The author
     */
    public function find($authorIdentifier)
    {
        $authorRequest = self::findRequest($authorIdentifier);

        $authorResult = $this->claireApi->getResult($authorRequest);
        ApiService::checkResponseSuccessful($authorResult);
        $author = AuthorFactory::create($authorResult->getContent());

        return $author;
    }

    /**
     * Returns all authors
     *
     * @return Author The authors
     */
    public function findAll()
    {
        $authorRequest = self::findAllRequest();

        $authorResult = $this->claireApi->getResult($authorRequest);
        ApiService::checkResponseSuccessful($authorResult);
        $authors = AuthorFactory::createCollection($authorResult->getContent());

        return $authors;
    }

    /**
     * Returns the author (ApiRequest)
     *
     * @param mixed $authorIdentifier The author identifier
     *
     * @return ApiRequest
     */
    public static function findRequest($authorIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_AUTHORS.$authorIdentifier);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns the authors (ApiRequest)
     *
     * @return ApiRequest
     */
    public static function findAllRequest()
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_AUTHORS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        //FIXME pagination

        return $apiRequest;
    }
}