<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use SimpleIT\ClaireExerciseBundle\Service\Serializer\SerializerInterface;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseObject\ExerciseObject;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\CoreBundle\Services\TransactionalService;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\ExerciseResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\OwnerResource;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResourceFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NoAuthorException;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseObject\ExerciseObjectFactory;
use SimpleIT\ClaireExerciseBundle\Repository\Exercise\ExerciseResource\ExerciseResourceRepository;
use SimpleIT\ClaireExerciseBundle\Service\User\UserService;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\CoreBundle\Annotation\Transactional;

/**
 * Service which manages the exercise resources
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseResourceService extends TransactionalService implements ExerciseResourceServiceInterface
{
    /**
     * @var ExerciseResourceRepository
     */
    private $exerciseResourceRepository;

    /**
     * @var OwnerResourceServiceInterface
     */
    private $ownerResourceService;

    /**
     * @var UserService
     */
    private $userService;

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
     * Set ownerResourceService
     *
     * @param OwnerResourceServiceInterface $ownerResourceService
     */
    public function setOwnerResourceService($ownerResourceService)
    {
        $this->ownerResourceService = $ownerResourceService;
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
     * Find the content of an exerciseResource entity by id
     *
     * @param $resId
     *
     * @return string The XML resource content
     */
    public function getContent($resId)
    {
        return $this->exerciseResourceRepository
            ->find($resId)
            ->getContent();
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
        $resEntity = $this->ownerResourceService->getByIdAndOwner($resId->getId(), $owner->getId());

        // if no resource, or wrong owner: error
        if (is_null($resEntity)) {
            throw new ApiNotFoundException("Resource cannot be found for this owner");
        }

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
        $resList = $this->ownerResourceService
            ->getResourcesFromConstraintsByOwner($oc, $owner);

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
     * @param int              $authorId
     *
     * @throws NoAuthorException
     * @return ExerciseResource
     */
    public function createFromResource(
        ResourceResource $resourceResource,
        $authorId = null
    )
    {
        $exerciseResource = ExerciseResourceFactory::createFromResource($resourceResource);

        if (!is_null($resourceResource->getAuthor())) {
            $authorId = $resourceResource->getAuthor();
        }
        if (is_null($authorId)) {
            throw new NoAuthorException();
        }
        $exerciseResource->setAuthor(
            $this->userService->get($authorId)
        );

        $reqResources = array();
        foreach ($resourceResource->getRequiredExerciseResources() as $reqRes) {
            $reqResources[] = $this->get($reqRes);
        }
        $exerciseResource->setRequiredExerciseResources(new ArrayCollection($reqResources));

        return $exerciseResource;
    }

    /**
     * Create and add an exerciseResource from a ResourceResource
     *
     * @param ResourceResource $resourceResource
     * @param int              $authorId
     *
     * @return ExerciseResource
     */
    public function createAndAdd(ResourceResource $resourceResource, $authorId)
    {
        $exerciseResource = $this->createFromResource($resourceResource, $authorId);

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
     * Create a resource object from an entity
     *
     * @param ExerciseResource $res
     *
     * @return CommonResource
     */
    private function createResourceObjectFromResourceEntity(
        ExerciseResource $res
    )
    {
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
     * @param OwnerResource $ownerResEntity
     *
     * @return ExerciseObject
     */
    private function getExerciseObjectFromEntity(
        OwnerResource $ownerResEntity
    )
    {
        $resEntity = $ownerResEntity->getResource();
        $resource = $this->createResourceObjectFromResourceEntity($resEntity);
        $requiredResources = $this->getRequiredResources($resEntity);

        return ExerciseObjectFactory::createExerciseObject(
            $resource,
            $ownerResEntity->getMetadata(),
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
     * @param int                   $authorId
     *
     * @return PaginatorInterface
     */
    public function getAll(
        $collectionInformation = null,
        $authorId = null
    )
    {
        $author = null;
        if (!is_null($authorId)) {
            $author = $this->userService->get($authorId);
        }

        return $this->exerciseResourceRepository->findAllBy($collectionInformation, $author);
    }
}
