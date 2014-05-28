<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\KnowledgeFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge\KnowledgeRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityService;
use SimpleIT\CoreBundle\Annotation\Transactional;

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
     * Required fields: type, title, [content or parent], owner, author, archived, metadata
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

        $reqKnowledges = array();
        foreach ($knowledgeResource->getRequiredKnowledges() as $reqKno) {
            $reqKnowledges[] = $this->get($reqKno);
        }
        $knowledge->setRequiredKnowledges(new ArrayCollection($reqKnowledges));

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

        if (!is_null($knowledgeResource->getRequiredKnowledges())) {
            $reqKnowledges = array();
            foreach ($knowledgeResource->getRequiredKnowledges() as $reqRes) {
                $reqKnowledges[] = $this->get($reqRes);
            }

            $knowledge->setRequiredKnowledges(new ArrayCollection($reqKnowledges));
        }

        return $knowledge;
    }

    /**
     * Add a requiredKnowledge to a knowledge
     *
     * @param int $knowledgeId The id of the requiring knowledge
     * @param int $reqKnoId    The id of the required knowledge
     *
     * @return Knowledge
     * @Transactional
     */
    public function addRequiredKnowledge(
        $knowledgeId,
        $reqKnoId
    )
    {
        /** @var Knowledge $reqKno */
        $reqKno = $this->get($reqKnoId);
        $this->entityRepository->addRequiredKnowledge($knowledgeId, $reqKno);

        return $this->get($knowledgeId);
    }

    /**
     * Delete a required knowledge
     *
     * @param int $knowledgeId The id of the requiring knowledge
     * @param int $reqKnoId    The id of the required knowledge
     *
     * @return Knowledge
     * @Transactional
     */
    public function deleteRequiredKnowledge(
        $knowledgeId,
        $reqKnoId
    )
    {
        /** @var Knowledge $reqKno */
        $reqKno = $this->get($reqKnoId);
        $this->entityRepository->deleteRequiredKnowledge($knowledgeId, $reqKno);
    }

    /**
     * Edit the required knowledges: remove all the old requirements and write the new ones
     * (saving).
     *
     * @param int             $knowledgeId        The id of the requiring knowledge
     * @param ArrayCollection $requiredKnowledges A collection of int: the id of knowledge
     *
     * @return Knowledge
     * @Transactional
     */
    public function editRequiredKnowledges($knowledgeId, ArrayCollection $requiredKnowledges)
    {
        $knowledge = $this->entityRepository->find($knowledgeId);

        $knowledgesCollection = array();
        foreach ($requiredKnowledges as $rk) {
            $knowledgesCollection[] = $this->entityRepository->find($rk);
        }
        $knowledge->setRequiredKnowledges(new ArrayCollection($knowledgesCollection));

        /** @var Knowledge $knowledge */
        $knowledge = $this->save($knowledge);

        return $knowledge->getRequiredKnowledges();
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
}
