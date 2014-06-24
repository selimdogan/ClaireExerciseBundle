<?php

namespace SimpleIT\ClaireExerciseBundle\Listener;

use SimpleIT\ClaireExerciseBundle\Model\Api\ApiResponse;
use SimpleIT\ClaireExerciseBundle\Service\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class ApiResponseListener
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiResponseListener
{
    /**
     * Serialization service
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Handle ApiResponse
     *
     * @param FilterResponseEvent $event
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\Api\ApiException
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response instanceof ApiResponse) {
            $format = 'json';
            $contentType = 'application/json';

            $resource = $response->getResource();
            if ($format != 'html') {
                $resource = $this->serializer->serialize(
                    $resource,
                    $format,
                    $response->getGroups()
                );
            }
            $response->setContent($resource);
            $response->headers->set('Content-type', $contentType);
        }
    }

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
