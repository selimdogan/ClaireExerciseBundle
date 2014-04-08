<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use
    SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoice\MultipleChoicePropositionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoiceQuestionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\PictureResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\TextResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\RequiredResourceByResourceRepository;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\ResourceRepository;

/**
 * Class ResourceService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceService
{
    /**
     * @var  ResourceRepository
     */
    private $resourceRepository;

    /**
     * @var RequiredResourceByResourceRepository
     */
    private $requiredResourceByResourceRepository;

    /**
     * @var OwnerResourceService
     */
    private $ownerResourceService;

    /**
     * Set resourceRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\ResourceRepository $resourceRepository
     */
    public function setResourceRepository($resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * Set requiredResourceByResourceRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\RequiredResourceByResourceRepository $requiredResourceByResourceRepository
     */
    public function setRequiredResourceByResourceRepository($requiredResourceByResourceRepository)
    {
        $this->requiredResourceByResourceRepository = $requiredResourceByResourceRepository;
    }

    /**
     * Set ownerResourceService
     *
     * @param \SimpleIT\ClaireAppBundle\Services\Exercise\Resource\OwnerResourceService $ownerResourceService
     */
    public function setOwnerResourceService($ownerResourceService)
    {
        $this->ownerResourceService = $ownerResourceService;
    }

    /**
     * @param int   $resourceId   Resource id
     * @param array $parameters   Parameters
     *
     * @return ResourceResource
     */
    public function getResourceToEdit($resourceId, array $parameters = array())
    {
        return $this->resourceRepository->findToEdit($resourceId, $parameters);
    }

    /**
     * Save a resource
     *
     * @param int              $resourceId Resource id
     * @param ResourceResource $resource
     * @param array            $parameters
     *
     * @return ResourceResource
     */
    public function save($resourceId, ResourceResource $resource, array $parameters = array())
    {
        return $this->resourceRepository->update($resourceId, $resource, $parameters);
    }

    /**
     * Save a multiple choice question
     *
     * @param       $resourceId
     * @param array $resourceContentArray
     *
     * @return ResourceResource
     */
    public function saveMCQuestion($resourceId, array $resourceContentArray)
    {
        $question = new MultipleChoiceQuestionResource();
        $question->setQuestion($resourceContentArray['question']);
        $question->setMaxNumberOfPropositions($resourceContentArray['maxProp']);
        $question->setMaxNumberOfRightPropositions($resourceContentArray['maxRightProp']);
        $question->setComment($resourceContentArray['comment']);
        $question->setDoNotShuffle(
            isset($resourceContentArray['doNotShuffle']) &&
            $resourceContentArray['doNotShuffle'] = 1
        );

        $propositions = array();
        foreach ($resourceContentArray['propositionText'] as $key => $propText) {
            $prop = new MultipleChoicePropositionResource();
            $prop->setText($propText);
            $prop->setRight(
                isset ($resourceContentArray['propositionRight'][$key]) &&
                $resourceContentArray['propositionRight'][$key] == 1
            );
            $prop->setForceUse(
                isset ($resourceContentArray['propositionForceUse'][$key]) &&
                $resourceContentArray['propositionForceUse'][$key] == 1
            );
            $propositions[] = $prop;
        }
        $question->setPropositions($propositions);

        $resource = new ResourceResource();
        $resource->setContent($question);

        return $this->save($resourceId, $resource);
    }

    /**
     * Save required resources
     *
     * @param       $resourceId
     * @param array $resourceContentArray
     *
     * @return ResourceResource
     */
    public function saveRequiredResource($resourceId, array $resourceContentArray)
    {
        $requiredResources = array();
        if (isset($resourceContentArray['requirement'])) {
            foreach ($resourceContentArray['requirement'] as $requirement) {
                $requiredResources[] = $requirement;
            }
        }

        return $this->requiredResourceByResourceRepository->update(
            $resourceId,
            new ArrayCollection($requiredResources)
        );
    }

    /**
     * Add a resource
     *
     * @param ResourceResource $resource
     *
     * @return ResourceResource
     */
    public function addFromType(ResourceResource $resource)
    {
        $content = null;
        switch ($resource->getType()) {
            case CommonResource::MULTIPLE_CHOICE_QUESTION:
                $content = new MultipleChoiceQuestionResource();
                $content->setQuestion('Ennoncé de la question');
                $content->setComment(
                    'Commentaire qui sera affiché à l\'apprenant quand il verra la correction'
                );
                $content->setMaxNumberOfPropositions(0);
                $content->setMaxNumberOfRightPropositions(0);
                break;
            case CommonResource::TEXT:
                $content = new TextResource();
                $content->setText('Exemple de texte');
                break;
            case CommonResource::PICTURE:
                $content = new PictureResource();
                $content->setSource('adresse/de/votre/image');
                break;
        }

        $resource->setContent($content);
        $resource->setRequiredExerciseResources(array());
        $resource = $this->add($resource);

        $this->ownerResourceService->addBasicFromResource($resource->getId());

        return $resource;
    }

    /**
     * Insert a new resource
     *
     * @param ResourceResource $resource
     *
     * @return ResourceResource
     */
    public function add(ResourceResource $resource)
    {
        return $this->resourceRepository->insert($resource);
    }

    /**
     * Get a resource
     *
     * @param int $resourceId Resource id
     *
     * @return ResourceResource
     */
    public function get($resourceId)
    {
        return $this->resourceRepository->findToEdit($resourceId);
    }

    /**
     * Delete a resource
     *
     * @param $resourceId
     */
    public function delete($resourceId)
    {
        $this->resourceRepository->delete($resourceId);
    }

    /**
     * Duplicate a resource in a new owner resource
     *
     * @param int $resourceId
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\OwnerResourceResource
     */
    public function duplicate($resourceId)
    {
        $resourceOld = $this->get($resourceId);
        $resourceOld->setAuthor(null);
        $resourceOld->setId(null);

        $resourceNew = $this->add($resourceOld);

        return $this->ownerResourceService->addBasicFromResource($resourceNew->getId());
    }
}
