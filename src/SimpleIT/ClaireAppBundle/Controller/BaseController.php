<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\AppBundle\Services\ApiService;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//FIXME container aware?
/**
 * Class SimpleIT\ClaireAppBundle\Controller
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class BaseController extends Controller
{
    /**
     * Get the Claire api service
     *
     * @param string $serviceName The service name
     *
     * @return SimpleIT\ClaireAppBundle\Api\ClaireApi
     */
    public function getClaireApi($serviceName = null)
    {
        $service = $this->get('claire_api_service');

        if (!is_null($serviceName)) {
            $service = $this->getService($serviceName);
        }
        return $service;
    }

    //TODO Check the method
    /**
     * Get the Claire Api Service asked
     *
     * @param string $serviceName The service name
     *
     * @return object A service
     */
    public function getService($serviceName)
    {
        return $this->get('claire_'.$serviceName.'_api');
    }

    //FIXME Remove
    /**
     * Check if ressource has been retrieved
     *
     * @param ApiResult $apiResult
     *
     * @throws NotFoundHttpException
     */
    public function checkObjectFound($apiResult)
    {
        if (!ApiService::isObjectFound($apiResult)) {
            throw new NotFoundHttpException('Ressource not found');
        }
    }
}
