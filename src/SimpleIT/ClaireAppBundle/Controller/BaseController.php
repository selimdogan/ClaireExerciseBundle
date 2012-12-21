<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Services\ApiService;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Base Controller
 */
class BaseController extends Controller
{
    /**
     * Get api service
     *
     * @return SimpleIT\ClaireAppBundle\Api\ClaireApi
     */
    public function getClaireApi($serviceName = null)
    {
        $service = $this->get('claire_api_service');

        if(!is_null($serviceName))
            $service = $this->getService($serviceName);

        return $service;
    }



    public function getService($serviceName)
    {
        return $this->get('claire_'.$serviceName.'_api');
    }

    /**
     * Check if ressource has been retrieved
     *
     * @param ApiResult $apiResult
     *
     * @throws NotFoundHttpException
     */
    public function checkObjectFound($apiResult)
    {
        if(!ApiService::isObjectFound($apiResult))
        {
            throw new NotFoundHttpException('Ressource not found');
        }

    }
}