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

        $this->transportService->submit('/oauth2/token', $param);

        if($this->transportService->getLastResponse()->getStatusCode() != 200)
        {
            throw new \Exception('Error from claire api : ' . $this->transportService->getLastResponse()->getContent());
        }

        return json_decode($this->transportService->getLastResponse()->getContent(), true);
    }

    public function requestAccessTokenWithClientCredentials()
    {
        $param = array(
            'grant_type' => 'client_credentials',
        );

        $this->transportService->submit('/oauth2/token', $param);

        if($this->transportService->getLastResponse()->getStatusCode() != 200)
        {
            throw new \Exception('Error from claire api : ' . $this->transportService->getLastResponse()->getContent());
        }

        return json_decode($this->transportService->getLastResponse()->getContent(), true);
    }
}

?>
