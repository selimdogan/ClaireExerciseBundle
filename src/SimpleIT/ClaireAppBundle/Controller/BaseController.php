<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;

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
    public function getCoursesApi()
    {
        return $this->get('claire_courses_api');
    }

    /**
     * Get api service
     *
     * @return SimpleIT\ClaireAppBundle\Api\ClaireApi
     */
    public function getCategoriesApi()
    {
        return $this->get('claire_categories_api');
    }
}
