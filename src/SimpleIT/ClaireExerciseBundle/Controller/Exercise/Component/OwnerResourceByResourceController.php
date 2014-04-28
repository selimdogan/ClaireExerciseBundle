<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class OwnerResourceByResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByResourceController extends AppController
{
    /**
     * Add a resource to the personal space: create an owner resource
     *
     * @param $resourceId
     *
     * @return JsonResponse
     */
    public function addToPersoAction($resourceId)
    {
        // TODO user
        $userId = 1000001;

        $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->addToPerso(
            $resourceId,
            $userId
        );

        return new JsonResponse(array(
            "id"       => $ownerResource->getId(),
            "metadata" => $ownerResource->getMetadata(),
            "type"     => $ownerResource->getType()
        ));
    }
}
