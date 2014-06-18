<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\DomainKnowledge;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResourceFactory;
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
            /** @var Knowledge $knowledge */
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
     * @return ApiGotResponse
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

            return new ApiGotResponse($knowledgeResources, array(
                'knowledge_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException('user');
        }
    }
}
