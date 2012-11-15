<?php

namespace SimpleIT\ClaireAppBundle\Services;

use SimpleIT\AppBundle\Services\ApiService;

/**
 * Description of OAuth2Service
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class OAuth2Service extends ApiService
{
    public function requestAccessTokenWithUserCredentials($username, $password)
    {
        $param = array(
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
        );

        $this->getTransportService()->submit('/oauth2/token', $param);

        if($this->getTransportService()->getLastResponse()->getStatusCode() != 200)
        {
            throw new \Exception('Error from claire api : ' . $this->getTransportService()->getLastResponse()->getContent());
        }

        return json_decode($this->getTransportService()->getLastResponse()->getContent(), true);
    }

    public function requestAccessTokenWithClientCredentials()
    {
        $param = array(
            'grant_type' => 'client_credentials',
        );

        $this->getTransportService()->submit('/oauth2/token', $param);

        if($this->getTransportService()->getLastResponse()->getStatusCode() != 200)
        {
            throw new \Exception('Error from claire api : ' . $this->getTransportService()->getLastResponse()->getContent());
        }

        return json_decode($this->getTransportService()->getLastResponse()->getContent(), true);
    }
}

?>
