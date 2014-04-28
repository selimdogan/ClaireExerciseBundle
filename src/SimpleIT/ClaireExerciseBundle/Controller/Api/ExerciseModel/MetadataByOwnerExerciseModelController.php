<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\MetadataResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModelMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API MetadataByOwnerExerciseModel Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerExerciseModelController extends ApiController
{
    /**
     * Get all metadata
     *
     * @param mixed                 $ownerExerciseModelId
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(
        $ownerExerciseModelId,
        CollectionInformation $collectionInformation
    )
    {
        try {
            $metadatas = $this->get('simple_it.exercise.exercise_model_metadata')->getAll(
                $collectionInformation,
                $ownerExerciseModelId
            );

            $metadataResources = MetadataResourceFactory::createCollection($metadatas);

            return new ApiGotResponse($metadataResources);
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Get a metadata
     *
     * @param mixed $ownerExerciseModelId
     * @param mixed $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerExerciseModelId, $metadataKey)
    {
        try {
            $metadata = $this->get('simple_it.exercise.exercise_model_metadata')
                ->getByOwnerExerciseModel(
                    $ownerExerciseModelId,
                    $metadataKey
                );

            $metadataResource = MetadataResourceFactory::create($metadata);

            return new ApiGotResponse($metadataResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a metadata
     *
     * @param Request $request
     * @param mixed   $ownerExerciseModelId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException;
     * @return ApiCreatedResponse
     */
    public function createAction(
        Request $request,
        $ownerExerciseModelId
    )
    {
        try {
            $metadata = $this->createMetadata($request);

            $metadata = $this->get(
                'simple_it.exercise.exercise_model_metadata'
            )->addToOwnerExerciseModel(
                    $ownerExerciseModelId,
                    $metadata
                );

            $metadataResource = MetadataResourceFactory::create($metadata);

            return new ApiCreatedResponse($metadataResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException();
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException();
        }
    }

    /**
     * Edit a metadata
     *
     * @param MetadataResource $metadata
     * @param mixed            $ownerExerciseModelId
     * @param string           $metadataKey
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiEditedResponse
     */
    public function editAction(
        MetadataResource $metadata,
        $ownerExerciseModelId,
        $metadataKey
    )
    {
        try {
            $this->validateResource($metadata, array('edit'));

            $metadata = $this->get(
                'simple_it.exercise.exercise_model_metadata'
            )->saveFromOwnerExerciseModel(
                    $ownerExerciseModelId,
                    $metadata,
                    $metadataKey
                );

            $metadataResource = MetadataResourceFactory::create($metadata);

            return new ApiEditedResponse($metadataResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Delete a metadata
     *
     * @param mixed $ownerExerciseModelId
     * @param mixed $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($ownerExerciseModelId, $metadataKey)
    {
        try {
            $this->get('simple_it.exercise.exercise_model_metadata')->removeFromOwnerExerciseModel(
                $ownerExerciseModelId,
                $metadataKey
            );

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Edit the list of metadata for this owner resource
     *
     * @param ArrayCollection $metadatas
     * @param int             $ownerExerciseModelId
     *
     * @return ApiEditedResponse
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     */
    public function editAllAction(ArrayCollection $metadatas, $ownerExerciseModelId)
    {
        try {
            $resources = $this->get('simple_it.exercise.owner_exercise_model')
                ->editMetadata
                (
                    $ownerExerciseModelId,
                    $metadatas
                );

            $resourceResource = MetadataResourceFactory::createCollection($resources);

            return new ApiEditedResponse($resourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        }
    }

    /**
     * Create a metadata from a request
     *
     * @param Request $request Request
     *
     * @return Metadata
     * @throws ApiBadRequestException
     */
    protected function createMetadata(Request $request)
    {
        $metadata = null;
        $content = json_decode($request->getContent(), true);
        if (!is_array($content) || count($content) !== 1) {
            throw new ApiBadRequestException('Metadata format is invalid');
        }

        foreach ($content as $key => $value) {
            $metadata = ExerciseModelMetadataFactory::create($key, $value);
        }

        return $metadata;
    }
}
