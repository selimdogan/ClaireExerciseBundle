<?php

namespace SimpleIT\ClaireExerciseBundle\Controller\Exercise\Component;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MetadataByOwnerResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerResourceController extends AppController
{
    /**
     * Edit the metadata (GET)
     *
     * @param int $ownerResourceId Owner Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editMetadataViewAction($ownerResourceId)
    {
        $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->get(
            $ownerResourceId
        );

        $misc = null;
        if (isset($ownerResource->getMetadata()[MetadataResource::MISC_METADATA_KEY])) {
            $misc = explode(
                ';',
                $ownerResource->getMetadata()[MetadataResource::MISC_METADATA_KEY]
            );
        }

        return $this->render(
            'SimpleITClaireExerciseBundle:Exercise/OwnerResource/Component:editMetadata.html.twig',
            array('ownerResource' => $ownerResource, 'misc' => $misc)
        );
    }

    /**
     * Edit the metadata (POST)
     *
     * @param Request $request         Request
     * @param int     $ownerResourceId Course id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metadataEditAction(Request $request, $ownerResourceId)
    {
        $resourceData = $request->request->all();
        $requiredResources = $this->get('simple_it.claire.exercise.owner_resource')->saveMetadata(
            $ownerResourceId,
            $resourceData
        );

        return new JsonResponse(array('requiredResources' => $requiredResources));
    }
}
