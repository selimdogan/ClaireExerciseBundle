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
    public function getApi()
    {
        return $this->get('claire_api');
    }
}
