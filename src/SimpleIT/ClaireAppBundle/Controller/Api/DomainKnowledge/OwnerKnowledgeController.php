<?php
namespace SimpleIT\ExerciseBundle\Controller\DomainKnowledge;

use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exercise\OwnerKnowledgeResource;
use SimpleIT\CoreBundle\Exception\ExistingObjectException;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ExerciseBundle\Model\Resources\OwnerKnowledgeResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Owner Knowledge Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeController extends ApiController
{
    /**
     * Get a specific OwnerKnowledge resource
     *
     * @param int $ownerKnowledgeId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerKnowledgeId)
    {
        try {
            $ownerKnowledge = $this->get('simple_it.exercise.owner_knowledge')->get(
                $ownerKnowledgeId
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
     *
     * @throws ApiBadRequestException
     * @return ApiPaginatedResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        $ownerKnowledges = $this->get('simple_it.exercise.owner_knowledge')->getAll(
            $collectionInformation
        );

        $oemResources = OwnerKnowledgeResourceFactory::createCollection($ownerKnowledges);

        return new ApiPaginatedResponse($oemResources, $ownerKnowledges, array(
            'owner_knowledge_list',
            'Default'
        ));
    }

    /**
     * Edit an owner knowledge
     *
     * @param OwnerKnowledgeResource $ownerKnowledgeResource
     * @param int                    $ownerKnowledgeId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(OwnerKnowledgeResource $ownerKnowledgeResource, $ownerKnowledgeId)
    {
        try {
            $this->validateResource($ownerKnowledgeResource, array('edit'));

            $ownerKnowledge = $this->get('simple_it.exercise.owner_knowledge')->edit
                (
                    $ownerKnowledgeResource,
                    $ownerKnowledgeId
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

    /**
     * Delete an owner resource
     *
     * @param int $ownerKnowledgeId
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($ownerKnowledgeId)
    {
        try {
            $this->get('simple_it.exercise.owner_knowledge')->remove($ownerKnowledgeId);

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerKnowledgeResource::RESOURCE_NAME);
        }
    }
}
