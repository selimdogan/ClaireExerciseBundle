<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\KnowledgeFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
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
     * Create an Knowledge object from a knowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param int               $authorId
     *
     * @throws NoAuthorException
     * @return Knowledge
     */
    public function createFromResource(
        $knowledgeResource,
        $authorId = null
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
     * Create or update a Knowledge object from a KnowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param Knowledge         $knowledge
     *
     * @throws NoAuthorException
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
     * @param $knowledgeId
     * @param $regKnoId
     *
     * @return Knowledge
     */
    public function addRequiredKnowledge(
        $knowledgeId,
        $regKnoId
    )
    {
        /** @var Knowledge $reqKno */
        $reqKno = $this->get($regKnoId);
        $this->entityRepository->addRequiredKnowledge($knowledgeId, $reqKno);

        return $this->get($knowledgeId);
    }

    /**
     * Delete a required knowledge
     *
     * @param $knowledgeId
     * @param $reqKnoId
     *
     * @return Knowledge
     */
    public function deleteRequiredResource(
        $knowledgeId,
        $reqKnoId
    )
    {
        /** @var Knowledge $reqKno */
        $reqKno = $this->get($reqKnoId);
        $this->entityRepository->deleteRequiredKnowledge($knowledgeId, $reqKno);
    }

    /**
     * Edit the required knowledges
     *
     * @param int             $knowledgeId
     * @param ArrayCollection $requiredKnowledges
     *
     * @return \Doctrine\Common\Collections\Collection
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
