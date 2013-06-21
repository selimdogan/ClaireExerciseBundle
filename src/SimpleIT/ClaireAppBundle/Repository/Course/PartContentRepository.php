<?php
/*
 * Copyright 2013 Simple IT
 *
 * This file is part of CLAIRE.
 *
 * CLAIRE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CLAIRE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CLAIRE. If not, see <http://www.gnu.org/licenses/>
 */

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;

/**
 * Class PartContentRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartContentRepository
{


    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses/';

    /** The base url for parts = '/parts/' */
    const URL_PART = '/parts/';

    protected $url = '';

    /**
     * @var  ClaireApi
     */
    protected $claireApi;

    /**
     * Set claireApi
     *
     * @param \SimpleIT\ClaireAppBundle\Api\ClaireApi $claireApi
     */
    public function setClaireApi($claireApi)
    {
        $this->claireApi = $claireApi;
    }

    public function update($courseIdentifier, $partIdentifier, $content, $format = 'text/html')
    {
        $request = new ApiRequest();
        $request->setBaseUrl(self::URL_COURSES . $courseIdentifier . self::URL_PART . $partIdentifier . '/content');
        $request->setMethod('PUT');
        $request->setRawData($content);
        $request->setFormat('text/html');
//        $request = $this->put($url);
//        $request->setFormat($format);
        $response = $this->claireApi->getResult($request);
        $content = $response->getContent();
//
        return $content;
    }

    /**
     * @param        $courseIdentifier
     * @param        $partIdentifier
     * @param string $format
     *
     * @return \SimpleIT\AppBundle\Model\ApiResult
     */
    public function find($courseIdentifier, $partIdentifier, $format = 'text/html')
    {
        $request = $this->findRequest($courseIdentifier, $partIdentifier, $format);
        $response = $this->claireApi->getResult($request);

        $content = $response->getContent();
        return $content;

    }

    /**
     * Returns the part (ApiRequest)
     *
     * @param mixed  $courseIdentifier  The course id | slug
     * @param mixed  $partIdentifier    The part id | slug
     * @param string $format            The requested format
     *
     * @return ApiRequest
     */
    public static function findRequest($courseIdentifier, $partIdentifier, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(
            self::URL_COURSES . $courseIdentifier . self::URL_PART . $partIdentifier . '/content'
        );
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat($format);

        return $apiRequest;
    }
}
