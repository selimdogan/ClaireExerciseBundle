<?php
namespace SimpleIT\ExerciseBundle\Controller\DomainKnowledge;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exercise\KnowledgeResource;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerKnowledgeResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ExerciseBundle\Model\Resources\OwnerKnowledgeResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Class OwnerKnowledgeByKnowledgeController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeByKnowledgeController extends ApiController
{
    /**
     * Get a specific OwnerKnowledge resource
     *
     * @param int $ownerKnowledgeId
     * @param int $knowledgeId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerKnowledgeId, $knowledgeId)
    {
        try {
            $ownerKnowledge = $this->get('simple_it.exercise.owner_knowledge')->getByIdAndKnowledge(
                $ownerKnowledgeId,
                $knowledgeId
            );
            $ownerKnowledgeResource = OwnerKnowledgeResourceFactory::create($ownerKnowledge);

            return new ApiGotResponse($ownerKnowledgeResource, array("owner_knowledge", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerKnowledgeResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of owner knowledges
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $knowledgeId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $knowledgeId
    )
    {
        try {
            $ownerKnowledges = $this->get('simple_it.exercise.owner_knowledge')->getAll(
                $collectionInformation,
                null,
                $knowledgeId
            );

            $ownerKnowledgeResources = OwnerKnowledgeResourceFactory::createCollection(
                $ownerKnowledges
            );

            return new ApiPaginatedResponse($ownerKnowledgeResources, $ownerKnowledges, array(
                'owner_knowledge_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a new owner knowledge (without metadata)
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $knowledgeId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(OwnerKnowledgeResource $ownerKnowledgeResource, $knowledgeId)
    {
        try {
            $userId = null;
            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $userId = $this->get('security.context')->getToken()->getUser()->getId();
            } else {
                throw new ApiBadRequestException(
                    'Owner must be authenticated to create a knowledge'
                );
            }

            $this->validateResource($ownerKnowledgeResource, array('create'));

            $ownerKnowledge = $this->get('simple_it.exercise.owner_knowledge')->createAndAdd
                (
                    $ownerKnowledgeResource,
                    $knowledgeId,
                    $userId
                );

            $ownerKnowledgeResource = OwnerKnowledgeResourceFactory::create($ownerKnowledge);

            return new ApiCreatedResponse($ownerKnowledgeResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerKnowledgeResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Edit an owner knowledge
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $ownerKnowledgeId
     * @param int                    $knowledgeId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(
        OwnerKnowledgeResource $ownerKnowledgeResource,
        $ownerKnowledgeId,
        $knowledgeId
    )
    {
        try {
            $this->validateResource($ownerKnowledgeResource, array('edit'));

            $ownerKnowledge = $this->get('simple_it.exercise.owner_knowledge')->edit
                (
                    $ownerKnowledgeResource,
                    $ownerKnowledgeId,
                    $knowledgeId
                );
            $ownerKnowledgeResource = OwnerKnowledgeResourceFactory::create($ownerKnowledge);

            return new ApiEditedResponse($ownerKnowledgeResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerKnowledgeResource::RESOURCE_NAME);
        } catch (ExistingObjectException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }
}
