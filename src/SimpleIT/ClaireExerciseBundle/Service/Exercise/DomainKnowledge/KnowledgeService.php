<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\KnowledgeFactory;
use SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidTypeException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResourceFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge\KnowledgeRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityService;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * Service which manages the domain knowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeService extends SharedEntityService implements KnowledgeServiceInterface
{
    const ENTITY_TYPE = 'knowledge';

    /**
     * @var KnowledgeRepository
     */
    protected $entityRepository;

    /**
     * @var FormulaServiceInterface
     */
    private $formulaService;

    /**
     * Set formulaService
     *
     * @param \SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge\FormulaServiceInterface $formulaService
     */
    public function setFormulaService($formulaService)
    {
        $this->formulaService = $formulaService;
    }

    /**
     * Create and add an entity from a resource (saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @return Knowledge
     */
    public function createFromResource(
        $knowledgeResource
    )
    {
        $this->validateKnowledgeResource($knowledgeResource);

        $knowledge = KnowledgeFactory::createFromResource($knowledgeResource);

        parent::fillFromResource($knowledge, $knowledgeResource);
        $knowledge = $this->computeRequirements($knowledgeResource, $knowledge);

        return $knowledge;
    }

    /**
     * Update an entity object from a Resource (no saving).
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of an entity can never be modified (ignored if not null)
     *
     * @param KnowledgeResource $knowledgeResource
     * @param Knowledge         $knowledge
     *
     * @return Knowledge
     */
    public function updateFromResource(
        $knowledgeResource,
        $knowledge
    )
    {
        parent::updateFromSharedResource($knowledgeResource, $knowledge, 'knowledge_storage');
        $knowledge = $this->computeRequirements($knowledgeResource, $knowledge);

        return $knowledge;
    }

    /**
     * Compute the requirements of the entity from the resource content (if content is empty,
     * no change)
     *
     * @param KnowledgeResource $knowledgeResource
     * @param Knowledge         $knowledge
     * @param bool              $import
     * @param int               $ownerId
     *
     * @return Knowledge
     */
    private function computeRequirements(
        $knowledgeResource,
        $knowledge,
        $import = false,
        $ownerId = null
    )
    {
        if ($knowledgeResource->getContent() != null) {
            // required knowledges
            $knowledgeResource = $this->computeRequiredKnowledgesFromResource(
                $knowledgeResource,
                $import,
                $ownerId
            );
            $reqKnowledges = array();
            foreach ($knowledgeResource->getRequiredKnowledges() as $reqKnowledge) {
                $reqKnowledges[] = $this->get($reqKnowledge);
            }
            $knowledge->setRequiredKnowledges(new ArrayCollection($reqKnowledges));
        }

        return $knowledge;
    }

    /**
     * Validate the knowledge resource
     *
     * @param KnowledgeResource $knowledgeResource
     *
     * @throws \SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException
     */
    public function validateKnowledgeResource(KnowledgeResource $knowledgeResource)
    {
        $knowledgeResource->getContent()->validate();

        if ($knowledgeResource->getType() === CommonKnowledge::FORMULA) {
            $content = $knowledgeResource->getContent();
            if (get_class($content) === KnowledgeResource::FORMULA_CLASS) {
                /** @var Formula $content */
                $this->formulaService->validateFormulaResource($content);
            } else {
                throw new InvalidKnowledgeException('Type are not consistent');
            }
        } else {
            throw new InvalidKnowledgeException('Unknown type of knowledge');
        }
    }

    /**
     * Check if the content of an exercise model is sufficient to generate exercises.
     *
     * @param string          $type
     * @param int             $parentEntityId
     * @param CommonKnowledge $content
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException
     * @return boolean True if the model is complete
     */
    protected function checkEntityComplete(
        $type,
        $parentEntityId,
        $content
    )
    {
        if ($parentEntityId === null) {
            switch ($type) {
                case CommonKnowledge::FORMULA:
                    /** @var Formula $content */
                    try {

                        $this->formulaService->validateFormulaResource($content);

                        return true;
                    } catch (InvalidKnowledgeException $ike) {
                        return false;
                    }
                    break;
                default:
                    throw new InconsistentEntityException('Invalid type');
            }
        } else {
            if ($content !== null) {
                throw new InconsistentEntityException('A model must be a pointer OR have a content');
            }
            try {

                $parentEntity = $this->get($parentEntityId);
            } catch (NonExistingObjectException $neoe) {
                throw new InconsistentEntityException('The parent knowledge cannot be found.');
            }

            return $parentEntity->getPublic();
        }
    }

    /**
     * Throws an exception if the content does not match the type
     *
     * @param $content
     * @param $type
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InvalidTypeException
     */
    protected function validateType(
        $content,
        $type
    )
    {
        if (($type === CommonKnowledge::FORMULA &&
            get_class($content) !== KnowledgeResource::FORMULA_CLASS)
        ) {
            throw new InvalidTypeException('Content does not match exercise model type');
        }
    }

    /**
     * Computes the required knowledges according to the content of the resource resource and
     * write it in the corresponding field of the output resource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param bool              $import
     * @param int               $ownerId
     *
     * @throws InvalidTypeException
     * @return KnowledgeResource
     */
    public function computeRequiredKnowledgesFromResource(
        $knowledgeResource,
        $import = false,
        $ownerId = null
    )
    {
        // no knowledge inter dependencies for the moment
        return $knowledgeResource;
    }

    /**
     * Subscribe to an entity: the new entity is a pointer to the parent entity. It has no
     * content and no metadata because these elements rely on the parent.
     *
     * @param int $ownerId        The id of the owner who wants to get the new pointer
     * @param int $parentEntityId The id of the parent entity
     *
     * @return Knowledge
     */
    public function subscribe($ownerId, $parentEntityId)
    {
        /** @var Knowledge $knowledge */
        $knowledge = parent::subscribe($ownerId, $parentEntityId);
        $knowledge->setRequiredKnowledges(new ArrayCollection());

        return $knowledge;
    }

    /**
     * Import an entity. Additionnal work, specific to entity type
     *
     * @param int $ownerId
     * @param Knowledge $entity The duplicata
     *
     * @return Knowledge
     */
    protected function importDetail($ownerId, $entity)
    {
        $resource = KnowledgeResourceFactory::create($entity);

        // requirement
        $entity = $this->computeRequirements($resource, $entity, true, $ownerId);

        $this->em->persist($entity);

        $this->em->flush();

        return $entity;
    }
}
