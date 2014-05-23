<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\DomainKnowledge;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResourceFactory;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class KnowledgeByUserController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeByUserController extends ApiController
{
    /**
     * Get a specific Owner knowledge resource
     *
     * @param int $ownerKnowledgeId
     * @param int $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerKnowledgeId, $ownerId)
    {
        try {
            $ownerKnowledge = $this->get('simple_it.exercise.knowledge')->getByIdAndOwner(
                $ownerKnowledgeId,
                $ownerId
            );
            $ownerKnowledgeResource = KnowledgeResourceFactory::create($ownerKnowledge);

            return new ApiGotResponse($ownerKnowledgeResource, array("owner_knowledge", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of owner knowledges
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $ownerId
    )
    {
        try {
            $ownerKnowledges = $this->get('simple_it.exercise.knowledge')->getAll(
                $collectionInformation,
                $ownerId
            );

            $ownerKnowledgeResources = KnowledgeResourceFactory::createCollection(
                $ownerKnowledges
            );

            return new ApiPaginatedResponse($ownerKnowledgeResources, $ownerKnowledges, array(
                'owner_knowledge_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException('user');
        }
    }
}
