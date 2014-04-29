<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\KnowledgeFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\DomainKnowledge\KnowledgeRepository;
use SimpleIT\ClaireExerciseBundle\Service\User\UserService;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the domain knowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeService extends TransactionalService implements KnowledgeServiceInterface
{
    /**
     * @var KnowledgeRepository
     */
    private $knowledgeRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var FormulaServiceInterface
     */
    private $formulaService;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Set knowledgeRepository
     *
     * @param KnowledgeRepository $knowledgeRepository
     */
    public function setKnowledgeRepository(
        KnowledgeRepository $knowledgeRepository
    )
    {
        $this->knowledgeRepository = $knowledgeRepository;
    }

    /**
     * Set userService
     *
     * @param UserService $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

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
     * Add a knowledge from a knowledgeResource
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     * @Transactional
     */
    public function add(Knowledge $knowledge)
    {
        $this->knowledgeRepository->insert($knowledge);

        return $knowledge;
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
        KnowledgeResource $knowledgeResource,
        $authorId = null
    )
    {
        $this->validateKnowledgeResource($knowledgeResource);

        $knowledge = KnowledgeFactory::createFromResource($knowledgeResource);

        if (!is_null($knowledgeResource->getAuthor())) {
            $authorId = $knowledgeResource->getAuthor();
        }
        if (is_null($authorId)) {
            throw new NoAuthorException();
        }
        $knowledge->setAuthor(
            $this->userService->get($authorId)
        );

        $reqKnowledges = array();
        foreach ($knowledgeResource->getRequiredKnowledges() as $reqKno) {
            $reqKnowledges[] = $this->get($reqKno);
        }
        $knowledge->setRequiredKnowledges(new ArrayCollection($reqKnowledges));

        return $knowledge;
    }

    /**
     * Create and add an knowledge from a KnowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param int               $authorId
     *
     * @return Knowledge
     */
    public function createAndAdd(KnowledgeResource $knowledgeResource, $authorId)
    {
        $knowledge = $this->createFromResource($knowledgeResource, $authorId);

        return $this->add($knowledge);
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
        KnowledgeResource $knowledgeResource,
        $knowledge
    )
    {
        if (!is_null($knowledgeResource->getRequiredKnowledges())) {
            $reqKnowledges = array();
            foreach ($knowledgeResource->getRequiredKnowledges() as $reqRes) {
                $reqKnowledges[] = $this->get($reqRes);
            }

            $knowledge->setRequiredKnowledges(new ArrayCollection($reqKnowledges));
        }

        if (!is_null($knowledgeResource->getContent())) {
            $context = SerializationContext::create();
            $context->setGroups(array('knowledge_storage', 'Default'));
            $knowledge->setContent(
                $this->serializer->serialize($knowledgeResource->getContent(), 'json', $context)
            );
        }

        return $knowledge;
    }

    /**
     * Save a knowledge given in form of a KnowledgeResource
     *
     * @param KnowledgeResource $knowledgeResource
     * @param int               $resourceId
     *
     * @return Knowledge
     */
    public function edit(
        KnowledgeResource $knowledgeResource,
        $resourceId
    )
    {
        $knowledge = $this->get($resourceId);
        $knowledge = $this->updateFromResource(
            $knowledgeResource,
            $knowledge
        );

        return $this->save($knowledge);
    }

    /**
     * Save a knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Knowledge
     * @Transactional
     */
    public function save(Knowledge $knowledge)
    {
        return $this->knowledgeRepository->update($knowledge);
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
        $reqRes = $this->get($regKnoId);
        $this->knowledgeRepository->addRequiredKnowledge($knowledgeId, $reqRes);

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
        $reqRes = $this->get($reqKnoId);
        $this->knowledgeRepository->deleteRequiredKnowledge($knowledgeId, $reqRes);
    }

    /**
     * Delete a knowledge
     *
     * @param $knowledgeId
     *
     * @Transactional
     */
    public function remove($knowledgeId)
    {
        $knowledge = $this->knowledgeRepository->find($knowledgeId);
        $this->knowledgeRepository->delete($knowledge);
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
        $knowledge = $this->knowledgeRepository->find($knowledgeId);

        $knowledgesCollection = array();
        foreach ($requiredKnowledges as $rk) {
            $knowledgesCollection[] = $this->knowledgeRepository->find($rk);
        }
        $knowledge->setRequiredKnowledges(new ArrayCollection($knowledgesCollection));

        return $this->save($knowledge)->getRequiredKnowledges();
    }

    /**
     * Get a knowledge by id
     *
     * @param $knowledgeId
     *
     * @return Knowledge
     */
    public function get($knowledgeId)
    {
        return $this->knowledgeRepository->find($knowledgeId);
    }

    /**
     * Get a list of knowledges
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $authorId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        CollectionInformation $collectionInformation = null,
        $authorId = null
    )
    {
        $author = null;
        if (!is_null($authorId)) {
            $author = $this->userService->get($authorId);
        }

        return $this->knowledgeRepository->findAllBy($collectionInformation, $author);
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
