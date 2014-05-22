<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResourceFactory;
use SimpleIT\ClaireExerciseBundle\Entity\ResourceMetadataFactory;
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
use SimpleIT\ClaireExerciseBundle\Service\Serializer\SerializerInterface;
use SimpleIT\ClaireExerciseBundle\Service\User\UserService;
use SimpleIT\CoreBundle\Annotation\Transactional;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;

/**
 * Service which manages the exercise resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResourceService extends SharedEntityService implements ExerciseResourceServiceInterface
{
    /**
     * @var ExerciseResourceRepository
     */
    private $exerciseResourceRepository;

    /**
     * @var KnowledgeServiceInterface
     */
    private $knowledgeService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var MetadataService
     */
    private $metadataService;

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
     * Set exerciseResourceRepository
     *
     * @param ExerciseResourceRepository $exerciseResourceRepository
     */
    public function setExerciseResourceRepository(
        ExerciseResourceRepository $exerciseResourceRepository
    )
    {
        $this->exerciseResourceRepository = $exerciseResourceRepository;
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
     * Set knowledgeService
     *
     * @param \SimpleIT\ClaireExerciseBundle\Service\Exercise\DomainKnowledge\KnowledgeServiceInterface $knowledgeService
     */
    public function setKnowledgeService($knowledgeService)
    {
        $this->knowledgeService = $knowledgeService;
    }

    /**
     * Set metadataService
     *
     * @param \SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource\MetadataService $metadataService
     */
    public function setMetadataService($metadataService)
    {
        $this->metadataService = $metadataService;
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
        $resEntity = $this->exerciseResourceRepository->findByIdAndOwner($resId->getId(), $owner);

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
        $resList = $this->exerciseResourceRepository->findResourcesFromConstraintsByOwner(
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
     * Add a resource from a ResourceResource
     *
     * @param ExerciseResource $exerciseResource
     *
     * @return ExerciseResource
     * @Transactional
     */
    public function add(ExerciseResource $exerciseResource)
    {
        $this->exerciseResourceRepository->insert($exerciseResource);

        return $exerciseResource;
    }

    /**
     * Create an ExerciseResource object from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function createFromResource(
        ResourceResource $resourceResource
    )
    {
        $exerciseResource = ExerciseResourceFactory::createFromResource($resourceResource);

        // author
        if (is_null($resourceResource->getAuthor())) {
            throw new NoAuthorException();
        }
        $exerciseResource->setAuthor(
            $this->userService->get($resourceResource->getAuthor())
        );

        // owner
        if (is_null($resourceResource->getOwner())) {
            throw new NoAuthorException('No owner for this model...');
        }
        $exerciseResource->setOwner(
            $this->userService->get($resourceResource->getOwner())
        );

        // parent model
        if (!is_null($resourceResource->getParent())) {
            $exerciseResource->setParent(
                $this->get($resourceResource->getParent())
            );
        }

        // fork from
        if (!is_null($resourceResource->getForkFrom())) {
            $exerciseResource->setForkFrom(
                $this->get($resourceResource->getForkFrom())
            );
        }

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

        // metadata
        $metadata = array();
        $resMetadata = $resourceResource->getMetadata();
        if (!empty($resMetadata)) {
            foreach ($resMetadata as $key => $value) {
                $md = ResourceMetadataFactory::create($key, $value);
                $md->setResource($exerciseResource);
                $metadata[] = $md;
            }
        }
        $exerciseResource->setMetadata(new ArrayCollection($metadata));

        return $exerciseResource;
    }

    /**
     * Create and add an exerciseResource from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     *
     * @return ExerciseResource
     */
    public function createAndAdd(ResourceResource $resourceResource)
    {
        $exerciseResource = $this->createFromResource($resourceResource);

        return $this->add($exerciseResource);
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
        ResourceResource $resourceResource,
        $exerciseResource
    )
    {
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

        if (!is_null($resourceResource->getType())) {
            $exerciseResource->setType($resourceResource->getType());
        }

        if (!is_null($resourceResource->getPublic())) {
            $exerciseResource->setPublic($resourceResource->getPublic());
        }

        if (!is_null($resourceResource->getContent())) {
            $context = SerializationContext::create();
            $context->setGroups(array('resource_storage', 'Default'));
            $exerciseResource->setContent(
                $this->serializer->jmsSerialize($resourceResource->getContent(), 'json', $context)
            );
        }

        return $exerciseResource;
    }

    /**
     * Save a resource given in form of a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param int              $resourceId
     *
     * @return ExerciseResource
     */
    public function edit(
        ResourceResource $resourceResource,
        $resourceId
    )
    {
        $exerciseResource = $this->get($resourceId);
        $exerciseResource = $this->updateFromResource(
            $resourceResource,
            $exerciseResource
        );

        return $this->save($exerciseResource);
    }

    /**
     * Save a resource
     *
     * @param ExerciseResource $exerciseResource
     *
     * @return ExerciseResource
     * @Transactional
     */
    public function save(ExerciseResource $exerciseResource)
    {
        return $this->exerciseResourceRepository->update($exerciseResource);
    }

    /**
     * Edit all the metadata of a resource
     *
     * @param int             $resourceId
     * @param ArrayCollection $metadatas
     *
     * @return Collection
     * @Transactional
     */
    public function editMetadata($resourceId, ArrayCollection $metadatas)
    {
        $resource = $this->exerciseResourceRepository->find($resourceId);

        $this->metadataService->deleteAllByExerciseResource($resourceId);

        $metadataCollection = array();
        foreach ($metadatas as $key => $value) {
            $md = ResourceMetadataFactory::create($key, $value);
            $md->setResource($resource);
            $metadataCollection[] = $md;
        }
        $resource->setMetadata(new ArrayCollection($metadataCollection));

        return $this->save($resource)->getMetadata();
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
        $reqRes = $this->get($reqResId);
        $this->exerciseResourceRepository->addRequiredResource($resourceId, $reqRes);

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
        $reqRes = $this->get($reqResId);
        $this->exerciseResourceRepository->deleteRequiredResource($resourceId, $reqRes);
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
        $resource = $this->exerciseResourceRepository->find($resourceId);

        $resourcesCollection = array();
        foreach ($requiredResources as $rr) {
            $resourcesCollection[] = $this->exerciseResourceRepository->find($rr);
        }
        $resource->setRequiredExerciseResources(new ArrayCollection($resourcesCollection));

        return $this->save($resource)->getRequiredExerciseResources();
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
        $reqKno = $this->knowledgeService->get($reqKnoId);
        $this->exerciseResourceRepository->addRequiredKnowledge($resourceId, $reqKno);

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
        $reqKno = $this->knowledgeService->get($reqKnoId);
        $this->exerciseResourceRepository->deleteRequiredKnowledge($exerciseModelId, $reqKno);
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
        $resource = $this->exerciseResourceRepository->find($resourceId);

        $reqKnowledgeCollection = array();
        foreach ($requiredKnowledges as $rk) {
            $reqKnowledgeCollection[] = $this->knowledgeService->get($rk);
        }
        $resource->setRequiredKnowledges(new ArrayCollection($reqKnowledgeCollection));

        return $this->save($resource)->getRequiredKnowledges();
    }

    /**
     * Delete a resource
     *
     * @param $resourceId
     *
     * @Transactional
     */
    public function remove($resourceId)
    {
        $resource = $this->exerciseResourceRepository->find($resourceId);
        $this->exerciseResourceRepository->delete($resource);
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
        while ($res->getContent() === null)
        {
            if ($res->getParent() === null)
            {
                throw new InvalidExerciseResourceException('Resource ' . $res->getId() .
                    ' has not content and no parent');
            }
            $res = $this->get($res->getParent());
        }

        $class = ResourceResource::getClass($res->getType());

        return $this->serializer->jmsDeserialize($res->getContent(), $class, 'json');
    }

    /**
     * Get the required resources of a resource entity under the form of an array of CommonResource
     *
     * @param ExerciseResource $resEntity
     *
     * @return array
     */
    private function getRequiredResources(
        ExerciseResource $resEntity
    )
    {
        $requiredResources = array();

        foreach ($resEntity->getRequiredExerciseResources() as $req) {
            /** @var ExerciseResource $req */
            $requiredResources[$req->getId()] = $this->createResourceObjectFromResourceEntity($req);
        }

        return $requiredResources;
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
        $requiredResources = $this->getRequiredResources($resEntity);

        return ExerciseObjectFactory::createExerciseObject(
            $resource,
            $resEntity->getMetadata(),
            $requiredResources
        );
    }

    /**
     * Get a resource by id
     *
     * @param $resourceId
     *
     * @return ExerciseResource
     */
    public function get(
        $resourceId
    )
    {
        return $this->exerciseResourceRepository->find($resourceId);
    }

    /**
     * Get a list of Resources
     *
     * @param CollectionInformation $collectionInformation The collection information
     * @param int                   $ownerId
     * @param int                   $authorId
     * @param int                   $parentModelId
     * @param int                   $forkFromModelId
     * @param boolean               $isRoot
     * @param boolean               $isPointer
     *
     * @return PaginatorInterface
     */
    public function getAll(
        $collectionInformation = null,
        $ownerId = null,
        $authorId = null,
        $parentModelId = null,
        $forkFromModelId = null,
        $isRoot = null,
        $isPointer = null
    )
    {
        $owner = null;
        if (!is_null($ownerId)) {
            $owner = $this->userService->get($ownerId);
        }

        $author = null;
        if (!is_null($authorId)) {
            $author = $this->userService->get($authorId);
        }

        $parentModel = null;
        if (!is_null($parentModelId)) {
            $parentModel = $this->get($parentModelId);
        }

        $forkFromModel = null;
        if (!is_null($forkFromModelId)) {
            $forkFromModel = $this->get($forkFromModelId);
        }

        return $this->exerciseResourceRepository->findAll(
            $collectionInformation,
            $owner,
            $author,
            $parentModel,
            $forkFromModel,
            $isRoot,
            $isPointer
        );
    }

    /**
     * Get an ExerciseResource by id and by owner
     *
     * @param int $resourceId
     * @param int $ownerId
     *
     * @return ExerciseResource
     */
    public function getByIdAndOwner($resourceId, $ownerId)
    {
        $owner = $this->userService->get($ownerId);

        return $this->exerciseResourceRepository->findByIdAndOwner($resourceId, $owner);
    }
}
