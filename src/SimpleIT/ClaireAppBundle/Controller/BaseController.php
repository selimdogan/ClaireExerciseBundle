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
    public function getApiService()
    {
        return $this->get('claire_api_service');
    }

    /**
     * Get api service
     *
     * @return SimpleIT\ClaireAppBundle\Api\ClaireApi
     */
    public function getCourseRouteService()
    {
        return $this->get('claire_courses_api');
    }

    /**
     * Get api service
     *
     * @return SimpleIT\ClaireAppBundle\Api\ClaireApi
     */
    public function getCategoryRouteService()
    {
        return $this->get('claire_categories_api');
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
