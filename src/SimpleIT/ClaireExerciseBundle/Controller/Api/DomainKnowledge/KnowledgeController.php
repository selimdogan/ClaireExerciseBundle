<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\DomainKnowledge;

use Doctrine\DBAL\DBALException;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException;
use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\EntityDeletionException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Knowledge controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeController extends ApiController
{
    /**
     * View action. View a knowledge.
     *
     * @param int $knowledgeId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($knowledgeId)
    {
        try {
            /** @var KnowledgeResource $knowledge */
            $knowledgeResource = $this->get('simple_it.exercise.knowledge')->getContentFullResource(
                $knowledgeId,
                $this->getUserId()
            );

            return new ApiGotResponse($knowledgeResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        }
    }

    /**
     * Get all items
     *
     * @param CollectionInformation $collectionInformation
     *
     * @throws \SimpleIT\ApiBundle\Exception\ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(CollectionInformation $collectionInformation)
    {
        try {
            $knowledges = $this->get('simple_it.exercise.knowledge')->getAll(
                $collectionInformation,
                $this->getUserId()
            );

            $knowledgeResources = KnowledgeResourceFactory::createCollection($knowledges);

            return new ApiGotResponse($knowledgeResources, array(
                'knowledge_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a new knowledge (without metadata)
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @return ApiResponse
     */
    public function createAction(KnowledgeResource $knowledgeResource)
    {
        try {
            $this->validateResource($knowledgeResource, array('create'));

            $userId = $this->getUserId();
            $knowledgeResource->setAuthor($userId);
            $knowledgeResource->setOwner($userId);

            /** @var Knowledge $knowledge */
            $knowledge = $this->get('simple_it.exercise.knowledge')->createAndAdd
                (
                    $knowledgeResource
                );

            $knowledgeResource = KnowledgeResourceFactory::create($knowledge);

            return new ApiCreatedResponse($knowledgeResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        } catch (InvalidKnowledgeException $ike) {
            throw new ApiBadRequestException($ike->getMessage());
        }
    }

    /**
     * Edit a knowledge
     *
     * @param KnowledgeResource $knowledgeResource   knowledge resource
     * @param int               $knowledgeId
     *
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     * @return ApiEditedResponse
     */
    public function editAction(KnowledgeResource $knowledgeResource, $knowledgeId)
    {
        try {
            $this->validateResource($knowledgeResource, array('edit', 'Default'));

            $knowledgeResource->setId($knowledgeId);
            $knowledge = $this->get('simple_it.exercise.knowledge')->edit
                (
                    $knowledgeResource,
                    $this->getUserId()
                );
            $knowledgeResource = KnowledgeResourceFactory::create($knowledge);

            return new ApiEditedResponse($knowledgeResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        } catch (DBALException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        } catch (NoAuthorException $nae) {
            throw new ApiBadRequestException($nae->getMessage());
        }
    }

    /**
     * Delete a knowledge
     *
     * @param int $knowledgeId
     *
     * @throws \SimpleIT\ApiBundle\Exception\ApiBadRequestException
     * @throws \SimpleIT\ApiBundle\Exception\ApiNotFoundException
     * @return \SimpleIT\ApiBundle\Model\ApiDeletedResponse
     */
    public function deleteAction($knowledgeId)
    {
        try {
            $this->get('simple_it.exercise.knowledge')->remove($knowledgeId, $this->getUserId());

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        } catch (EntityDeletionException $ede) {
            throw new ApiBadRequestException($ede->getMessage());
        }
    }
}
