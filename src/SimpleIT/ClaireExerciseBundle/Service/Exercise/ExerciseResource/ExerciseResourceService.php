<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResourceFactory;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidExerciseResourceException;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseObject\ExerciseObjectFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\ExerciseResourceRepository;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge\KnowledgeServiceInterface;
use SimpleIT\ClaireExerciseBundle\Service\Exercise\SharedEntity\SharedEntityService;
use SimpleIT\CoreBundle\Annotation\Transactional;

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
     * Get a resource in the form of an ExerciseObject
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
     * Create an ExerciseResource object from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function createFromResource($resourceResource)
    {
        $exerciseResource = ExerciseResourceFactory::createFromResource($resourceResource);

        parent::fillFromResource($exerciseResource, $resourceResource);

        // required resources
        $reqResources = array();
        foreach ($resourceResource->getRequiredExerciseResources() as $reqRes) {
            $reqResources[] = $this->get($reqRes);
        }
        $exerciseResource->setRequiredExerciseResources(new ArrayCollection($reqResources));

        // required knowledges
        $reqKnowledges = array();
        foreach ($resourceResource->getRequiredKnowledges() as $reqKnowledge) {
            $reqKnowledges[] = $this->knowledgeService->get($reqKnowledge);
        }
        $exerciseResource->setRequiredKnowledges(new ArrayCollection($reqKnowledges));

        return $exerciseResource;
    }

    /**
     * Create or update an ExerciseResource object from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param ExerciseResource $exerciseResource
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function updateFromResource(
        $resourceResource,
        $exerciseResource
    )
    {
        parent::updateFromSharedResource($resourceResource, $exerciseResource, 'resource_storage');

        if (!is_null($resourceResource->getRequiredExerciseResources())) {
            $reqResources = array();
            foreach ($resourceResource->getRequiredExerciseResources() as $reqRes) {
                $reqResources[] = $this->get($reqRes);
            }
            $exerciseResource->setRequiredExerciseResources(new ArrayCollection($reqResources));
        }

        if (!is_null($resourceResource->getRequiredKnowledges())) {
            $reqKnowledges = array();
            foreach ($resourceResource->getRequiredKnowledges() as $reqKnowledge) {
                $reqKnowledges[] = $this->knowledgeService->get($reqKnowledge);
            }
            $exerciseResource->setRequiredKnowledges(new ArrayCollection($reqKnowledges));
        }

        return $exerciseResource;
    }

    /**
     * Add a requiredResource to a resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @return ExerciseResource
     */
    public function addRequiredResource(
        $resourceId,
        $reqResId
    )
    {
        /** @var ExerciseResource $reqRes */
        $reqRes = $this->get($reqResId);
        $this->entityRepository->addRequiredResource($resourceId, $reqRes);

        return $this->get($resourceId);
    }

    /**
     * Delete a required resource
     *
     * @param $resourceId
     * @param $reqResId
     *
     * @return ExerciseResource
     */
    public function deleteRequiredResource(
        $resourceId,
        $reqResId
    )
    {
        /** @var ExerciseResource $reqRes */
        $reqRes = $this->get($reqResId);
        $this->entityRepository->deleteRequiredResource($resourceId, $reqRes);
    }

    /**
     * Edit the required resources
     *
     * @param int             $resourceId
     * @param ArrayCollection $requiredResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function editRequiredResource($resourceId, ArrayCollection $requiredResources)
    {
        $resource = $this->entityRepository->find($resourceId);

        $resourcesCollection = array();
        foreach ($requiredResources as $rr) {
            $resourcesCollection[] = $this->entityRepository->find($rr);
        }
        $resource->setRequiredExerciseResources(new ArrayCollection($resourcesCollection));

        /** @var ExerciseResource $resource */
        $resource = $this->save($resource);

        return $resource->getRequiredExerciseResources();
    }

    /**
     * Add a required knowledge to an exercise model
     *
     * @param $resourceId
     * @param $reqKnoId
     *
     * @return ExerciseResource
     */
    public function addRequiredKnowledge(
        $resourceId,
        $reqKnoId
    )
    {
        /** @var Knowledge $reqKno */
        $reqKno = $this->knowledgeService->get($reqKnoId);
        $this->entityRepository->addRequiredKnowledge($resourceId, $reqKno);

        return $this->get($resourceId);
    }

    /**
     * Delete a required knowledge
     *
     * @param $exerciseModelId
     * @param $reqKnoId
     *
     * @return ExerciseResource
     */
    public function deleteRequiredKnowledge(
        $exerciseModelId,
        $reqKnoId
    )
    {
        /** @var Knowledge $reqKno */
        $reqKno = $this->knowledgeService->get($reqKnoId);
        $this->entityRepository->deleteRequiredKnowledge($exerciseModelId, $reqKno);
    }

    /**
     * Edit the required knowledges
     *
     * @param int             $resourceId
     * @param ArrayCollection $requiredKnowledges
     *
     * @return ExerciseResource
     */
    public function editRequiredKnowledges(
        $resourceId,
        ArrayCollection $requiredKnowledges
    )
    {
        $resource = $this->entityRepository->find($resourceId);

        $reqKnowledgeCollection = array();
        foreach ($requiredKnowledges as $rk) {
            $reqKnowledgeCollection[] = $this->knowledgeService->get($rk);
        }
        $resource->setRequiredKnowledges(new ArrayCollection($reqKnowledgeCollection));

        /** @var ExerciseResource $resource */
        $resource = $this->save($resource);

        return $resource->getRequiredKnowledges();
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
}
