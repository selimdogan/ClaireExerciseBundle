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
     * Get a specific knowledge resource
     *
     * @param int $knowledgeId
     * @param int $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($knowledgeId, $ownerId)
    {
        try {
            $knowledge = $this->get('simple_it.exercise.knowledge')->getByIdAndOwner(
                $knowledgeId,
                $ownerId
            );
            $knowledgeResource = KnowledgeResourceFactory::create($knowledge);

            return new ApiGotResponse($knowledgeResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(KnowledgeResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of knowledges
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
            $knowledges = $this->get('simple_it.exercise.knowledge')->getAll(
                $collectionInformation,
                $ownerId
            );

            $knowledgeResources = KnowledgeResourceFactory::createCollection(
                $knowledges
            );

            return new ApiPaginatedResponse($knowledgeResources, $knowledges, array(
                'knowledge_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException('user');
        }
    }
}
