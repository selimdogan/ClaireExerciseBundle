<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\AppBundle\Controller\AppController;
use SimpleIT\AppBundle\Util\RequestUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimpleIT\ApiResourcesBundle\Exercise\MetadataResource;

/**
 * Class MetadataByOwnerExerciseModelController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerExerciseModelController extends AppController
{
    /**
     * Edit the metadata (GET)
     *
     * @param int $ownerExerciseModelId Owner Resource id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editMetadataViewAction($ownerExerciseModelId)
    {
        $ownerExerciseModel = $this->get('simple_it.claire.exercise.owner_exercise_model')->get(
            $ownerExerciseModelId
        );

        $misc = null;
        if (isset($ownerExerciseModel->getMetadata()[MetadataResource::MISC_METADATA_KEY])) {
            $misc = explode(';', $ownerExerciseModel->getMetadata()[MetadataResource::MISC_METADATA_KEY]);
        }

        return $this->render(
            'SimpleITClaireAppBundle:Exercise/OwnerExerciseModel/Component:editMetadata.html.twig',
            array('ownerExerciseModel' => $ownerExerciseModel, 'misc' => $misc)
        );
    }

    /**
     * Edit the metadata (POST)
     *
     * @param Request $request              Request
     * @param int     $ownerExerciseModelId Owner exercise model id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metadataEditAction(Request $request, $ownerExerciseModelId)
    {
        $resourceData = $request->request->all();
        $requiredResources = $this->get(
            'simple_it.claire.exercise.owner_exercise_model'
        )->saveMetadata(
            $ownerExerciseModelId,
            $resourceData
        );

        return new JsonResponse(array('requiredResources' => $requiredResources));
    }
}
