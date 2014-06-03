<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResourceFactory;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\InconsistentEntityException;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidExerciseResourceException;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidTypeException;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseObject\ExerciseObjectFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use
    SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\MultipleChoice\MultipleChoicePropositionResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\MultipleChoiceQuestionResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\OpenEndedQuestionResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\PictureResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\ResourceId;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\SequenceElement;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\SequenceResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\TextResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\ExerciseResourceRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge\KnowledgeServiceInterface;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityService;
use SimpleIT\CoreBundle\Annotation\Transactional;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;

/**
 * Service which manages the exercise resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResourceService extends SharedEntityService implements ExerciseResourceServiceInterface
{
    const ENTITY_TYPE = 'resource';

    /**
     * @var KnowledgeServiceInterface
     */
    private $knowledgeService;

    /**
     * @var ExerciseResourceRepository
     */
    protected $entityRepository;

    /**
     * Set knowledgeService
     *
     * @param \SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge\KnowledgeServiceInterface $knowledgeService
     */
    public function setKnowledgeService($knowledgeService)
    {
        $this->knowledgeService = $knowledgeService;
    }

    /**
     * Get a resource in the form of an ExerciseObject (useful for exercise generation)
     *
     * @param ObjectId $resId
     * @param User     $owner
     *
     * @throws ApiNotFoundException
     * @return ExerciseObject
     */
    public function getExerciseObject(ObjectId $resId, User $owner)
    {
        $resEntity = $this->entityRepository->findByIdAndOwner($resId->getId(), $owner);

        return $this->getExerciseObjectFromEntity($resEntity);
    }

    /**
     * Returns a list of ExerciseObjects matching the constraints
     *
     * @param ObjectConstraints $oc The constraints
     * @param int               $numberOfObjects
     * @param User              $owner
     *
     * @return array An array of ExerciseObjects
     */
    public function getExerciseObjectsFromConstraints(
        ObjectConstraints $oc,
        $numberOfObjects,
        User $owner
    )
    {
        $resList = $this->entityRepository->findResourcesFromConstraintsByOwner(
            $oc,
            $owner
        );

        $objList = array();

        while ($numberOfObjects > 0 && count($resList)) {
            $key = array_rand($resList);
            $res = $resList[$key];
            $objList[] = $this->getExerciseObjectFromEntity($res);
            unset($resList[$key]);
            $numberOfObjects--;
        }

        return $objList;
    }

    /**
     * Create an entity from a resource (no saving).
     * Required fields: type, title, [content or parent], draft, owner, author, archived, metadata
     * Must be null: id
     * Not used (computed) : required resources, required knowledge
     *
     * @param ResourceResource $resourceResource
     *
     * @return ExerciseResource
     */
    public function createFromResource($resourceResource)
    {
        $exerciseResource = ExerciseResourceFactory::createFromResource($resourceResource);

        parent::fillFromResource($exerciseResource, $resourceResource);
        $exerciseResource = $this->computeRequirements($exerciseResource, $resourceResource);

        return $exerciseResource;
    }

    /**
     * Update an entity object from a Resource (no saving).
     * Only the fields that are not null in the resource are taken in account to edit the entity.
     * The id of an entity can never be modified (ignored if not null)
     *
     * @param ResourceResource $resourceResource
     * @param                  $exerciseResource
     *
     * @return ExerciseResource
     */
    public function updateFromResource(
        $resourceResource,
        $exerciseResource
    )
    {
        parent::updateFromSharedResource($resourceResource, $exerciseResource, 'resource_storage');
        $exerciseResource = $this->computeRequirements($exerciseResource, $resourceResource);

        return $exerciseResource;
    }

    /**
     * Compute the requirements of the entity from the resource content (if content is empty,
     * no change)
     *
     * @param ExerciseResource $exerciseResource
     * @param ResourceResource $resourceResource
     *
     * @return ExerciseResource
     */
    private function computeRequirements($exerciseResource, $resourceResource)
    {
        if ($resourceResource->getContent() != null) {
            // required resources
            $resourceResource = $this->computeRequiredResourcesFromResource($resourceResource);
            $reqResources = array();
            foreach ($resourceResource->getRequiredExerciseResources() as $reqRes) {
                $reqResources[] = $this->get($reqRes);
            }
            $exerciseResource->setRequiredExerciseResources(new ArrayCollection($reqResources));

            // required knowledges
            $resourceResource = $this->computeRequiredKnowledgesFromResource($resourceResource);
            $reqKnowledges = array();
            foreach ($resourceResource->getRequiredKnowledges() as $reqKnowledge) {
                $reqKnowledges[] = $this->knowledgeService->get($reqKnowledge);
            }
            $exerciseResource->setRequiredKnowledges(new ArrayCollection($reqKnowledges));
        }

        return $exerciseResource;
    }

    /**
     * Create a resource object from an entity
     *
     * @param ExerciseResource $res
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\InvalidExerciseResourceException
     * @return CommonResource
     */
    private function createResourceObjectFromResourceEntity(
        ExerciseResource $res
    )
    {
        while ($res->getContent() === null) {
            if ($res->getParent() === null) {
                throw new InvalidExerciseResourceException('Resource ' . $res->getId() .
                ' has not content and no parent');
            }
            $res = $this->get($res->getParent()->getId());
        }

        $class = ResourceResource::getSerializationClass($res->getType());

        return $this->serializer->jmsDeserialize($res->getContent(), $class, 'json');
    }

    /**
     * Get an object from an entity
     *
     * @param ExerciseResource $resEntity
     *
     * @return ExerciseObject
     */
    private function getExerciseObjectFromEntity(
        ExerciseResource $resEntity
    )
    {
        $resource = $this->createResourceObjectFromResourceEntity($resEntity);
        $requiredResources = array();

        foreach ($resEntity->getRequiredExerciseResources() as $req) {
            /** @var ExerciseResource $req */
            $requiredResources[$req->getId()] = $this->createResourceObjectFromResourceEntity($req);
        }

        return ExerciseObjectFactory::createExerciseObject(
            $resource,
            $resEntity->getMetadata(),
            $requiredResources
        );
    }

    /**
     * Check if the content of a resource is sufficient to use it to generate exercises.
     *
     * @param string         $type
     * @param int            $parentEntityId
     * @param CommonResource $content
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
                case CommonResource::PICTURE:
                    /** @var PictureResource $content */
                    return $this->checkPictureComplete($content);
                    break;
                case CommonResource::TEXT:
                    /** @var TextResource $content */
                    return $this->checkTextComplete($content);
                    break;
                case CommonResource::OPEN_ENDED_QUESTION:
                    /** @var OpenEndedQuestionResource $content */
                    return $this->checkOEQComplete($content);
                    break;
                case CommonResource::MULTIPLE_CHOICE_QUESTION:
                    /** @var MultipleChoiceQuestionResource $content */
                    return $this->checkMCQComplete($content);
                    break;
                case CommonResource::SEQUENCE:
                    /** @var SequenceResource $content */
                    return $this->checkSequenceComplete($content);
                    break;
                default:
                    throw new InconsistentEntityException('Invalid type');
            }
        } else {
            if ($content !== null) {
                throw new InconsistentEntityException('A model must be a pointer OR have a content');
            }
            try {

                $parentModel = $this->get($parentEntityId);
            } catch (NonExistingObjectException $neoe) {
                throw new InconsistentEntityException('The parent model cannot be found.');
            }

            return $parentModel->getPublic();
        }
    }

    /**
     * Check if a picture resource is complete
     *
     * @param PictureResource $content
     *
     * @return bool
     */
    private function checkPictureComplete(
        PictureResource $content
    )
    {
        return $content->getSource() !== null;
    }

    /**
     * Check if a text resource is complete
     *
     * @param TextResource $content
     *
     * @return bool
     */
    private function checkTextComplete(
        TextResource $content
    )
    {
        return $content->getText() !== null;
    }

    /**
     * Check if an open ended question resource is complete
     *
     * @param OpenEndedQuestionResource $content
     *
     * @return bool
     */
    private function checkOEQComplete(
        OpenEndedQuestionResource $content
    )
    {
        if ($content->getQuestion() === null) {
            return false;
        }

        if ($content->getSolutions() === null || count($content->getSolutions()) == 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if a multiple choice question resource is complete
     *
     * @param MultipleChoiceQuestionResource $content
     *
     * @return bool
     */
    private function checkMCQComplete(
        MultipleChoiceQuestionResource $content
    )
    {
        if ($content->getQuestion() === null) {
            return false;
        }

        if ($content->getPropositions() === null || count($content->getPropositions()) == 0) {
            return false;
        }

        /** @var MultipleChoicePropositionResource $proposition */
        foreach ($content->getPropositions() as $proposition) {
            if ($proposition->getText() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a sequence resource is complete
     *
     * @param SequenceResource $content
     *
     * @return bool
     */
    private function checkSequenceComplete(
        SequenceResource $content
    )
    {
        if ($content->getSequenceType() === null) {
            return false;
        }

        try {
            $content->validate();
        } catch (InvalidExerciseResourceException $ire) {
            return false;
        }

        return true;
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
        if (($type === CommonResource::MULTIPLE_CHOICE_QUESTION &&
                get_class($content) !== ResourceResource::MULTIPLE_CHOICE_QUESTION_CLASS)
            || ($type === CommonResource::OPEN_ENDED_QUESTION &&
                get_class($content) !== ResourceResource::OPEN_ENDED_QUESTION_CLASS)
            || ($type === CommonResource::SEQUENCE &&
                get_class($content) !== ResourceResource::SEQUENCE_CLASS)
            || ($type === CommonResource::TEXT &&
                get_class($content) !== ResourceResource::TEXT_CLASS)
            || ($type === CommonResource::PICTURE &&
                get_class($content) !== ResourceResource::PICTURE_CLASS)
        ) {
            throw new InvalidTypeException('Content does not match exercise model type');
        }
    }

    /**
     * Computes the required resources according to the content of the resource resource and
     * write it in the corresponding field of the output resource
     *
     * @param ResourceResource $resourceResource
     *
     * @throws InvalidTypeException
     * @return ResourceResource
     */
    public function computeRequiredResourcesFromResource($resourceResource)
    {
        switch (get_class($resourceResource->getContent())) {
            case ResourceResource::PICTURE_CLASS:
            case ResourceResource::TEXT_CLASS:
            case ResourceResource::MULTIPLE_CHOICE_QUESTION_CLASS:
            case ResourceResource::OPEN_ENDED_QUESTION_CLASS:
                $resourceResource->setRequiredExerciseResources(array());
                break;
            case ResourceResource::SEQUENCE_CLASS:
                $resourceResource = $this->computeRequiredResourcesForSequence($resourceResource);
                break;
            default:
                throw new InvalidTypeException('Unknown type:' . $resourceResource->getType());
        }

        return $resourceResource;
    }

    /**
     * Compute the required resources for a sequence resource
     *
     * @param ResourceResource $resourceResource
     *
     * @return ResourceResource
     */
    private function computeRequiredResourcesForSequence($resourceResource)
    {
        /** @var SequenceResource $content */
        $content = $resourceResource->getContent();

        if ($content->getTextObjectId() !== null) {
            $resourceResource->setRequiredExerciseResources(array($content->getTextObjectId()));
        } else {
            $resourceResource->setRequiredExerciseResources(
                $this->computeRequiredResourcesForSequenceFromBlock(
                    $content->getMainBlock()
                )
            );

        }

        return $resourceResource;
    }

    /**
     * Get all the required resources in this block
     *
     * @param SequenceBlock $block
     *
     * @return array
     */
    private function computeRequiredResourcesForSequenceFromBlock($block)
    {
        $reqRes = array();

        foreach ($block->getElements() as $element) {
            if (get_class($element) === SequenceElement::RESOURCE_ID_CLASS) {
                /** @var ResourceId $element */
                $reqRes[] = $element->getResourceId();
            } elseif (get_class($element) === SequenceElement::BLOCK_CLASS) {
                /** @var SequenceBlock $element */
                $reqRes = array_unique(
                    $this->computeRequiredResourcesForSequenceFromBlock($element)
                );
            }
        }

        return $reqRes;
    }

    /**
     * Computes the required knowledges according to the content of the resource resource and
     * write it in the corresponding field of the output resource
     *
     * @param ResourceResource $resourceResource
     *
     * @throws InvalidTypeException
     * @return ResourceResource
     */
    public function computeRequiredKnowledgesFromResource($resourceResource)
    {
        $reqKno = array();

        /** @var LocalFormula $formula */
        foreach ($resourceResource->getContent()->getFormulas() as $formula) {
            $reqKno[] = $formula->getFormulaId();
        }

        $resourceResource->setRequiredKnowledges(array_unique($reqKno));

        return $resourceResource;
    }

    /**
     * Subscribe to an entity: the new entity is a pointer to the parent entity. It has no
     * content and no metadata because these elements rely on the parent.
     *
     * @param int $ownerId        The id of the owner who wants to get the new pointer
     * @param int $parentEntityId The id of the parent entity
     *
     * @return ExerciseResource
     */
    public function subscribe($ownerId, $parentEntityId)
    {
        /** @var ExerciseResource $resource */
        $resource = parent::subscribe($ownerId, $parentEntityId);
        $resource->setRequiredExerciseResources(new ArrayCollection());
        $resource->setRequiredKnowledges(new ArrayCollection());

        return $resource;
    }
}
