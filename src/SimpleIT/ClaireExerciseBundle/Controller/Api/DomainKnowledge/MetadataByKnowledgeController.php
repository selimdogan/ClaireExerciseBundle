<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\KnowledgeMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API MetadataByKnowledge Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByKnowledgeController extends ApiController
{
    /**
     * Get all metadata
     *
     * @param mixed                 $knowledgeId
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(
        $knowledgeId,
        CollectionInformation $collectionInformation
    )
    {
        try {
            $metadatas = $this->get('simple_it.exercise.knowledge_metadata')->getAll(
                $collectionInformation,
                $knowledgeId
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
     * @param mixed $knowledgeId
     * @param mixed $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($knowledgeId, $metadataKey)
    {
        try {
            $metadata = $this->get('simple_it.exercise.knowledge_metadata')->getByEntity(
                $knowledgeId,
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
     * @param mixed   $knowledgeId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException;
     * @return ApiCreatedResponse
     */
    public function createAction(
        Request $request,
        $knowledgeId
    )
    {
        try {
            $metadata = $this->createMetadata($request);

            $metadata = $this->get('simple_it.exercise.knowledge_metadata')->addToEntity(
                $knowledgeId,
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
     * @param mixed            $knowledgeId
     * @param string           $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiEditedResponse
     */
    public function editAction(
        MetadataResource $metadata,
        $knowledgeId,
        $metadataKey
    )
    {
        try {
            $this->validateResource($metadata, array('edit'));

            $metadata = $this->get('simple_it.exercise.knowledge_metadata')->saveFromEntity(
                $knowledgeId,
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
     * @param mixed $knowledgeId
     * @param mixed $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($knowledgeId, $metadataKey)
    {
        try {
            $this->get('simple_it.exercise.knowledge_metadata')->removeFromEntity(
                $knowledgeId,
                $metadataKey
            );

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Edit the list of metadata for this knowledge
     *
     * @param ArrayCollection $metadatas
     * @param int             $knowledgeId
     *
     * @return ApiEditedResponse
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     */
    public function editAllAction(ArrayCollection $metadatas, $knowledgeId)
    {
        try {
            $knowledge = $this->get('simple_it.exercise.knowledge')
                ->editMetadata
                (
                    $knowledgeId,
                    $metadatas
                );

            $knowledgeResource = MetadataResourceFactory::createCollection($knowledge);

            return new ApiEditedResponse($knowledgeResource);

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
        if (!is_array($content) || count($content) != 1) {
            throw new ApiBadRequestException('Metadata format is invalid');
        }

        foreach ($content as $key => $value) {
            $metadata = KnowledgeMetadataFactory::create($key, $value);
        }

        return $metadata;
    }
}
