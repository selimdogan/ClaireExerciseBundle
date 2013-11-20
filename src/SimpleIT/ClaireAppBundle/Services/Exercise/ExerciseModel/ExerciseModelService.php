<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\Model as GroupItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\Model as MultipleChoice;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\QuestionBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\Model as OrderItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems\Model as PairItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\MetadataConstraint;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ModelDocument;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectConstraints;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectId;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModel\ExerciseModelRepository;
use
    SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModel\RequiredResourceByExerciseModelRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExerciseModelService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelService
{
    /**
     * @var  ExerciseModelRepository
     */
    private $exerciseModelRepository;

    /**
     * @var RequiredResourceByExerciseModelRepository
     */
    private $requiredResourceByExerciseModelRepository;

    /**
     * @var OwnerExerciseModelService
     */
    private $ownerExerciseModelService;

    /**
     * Set exerciseModelRepository
     *
     * @param ExerciseModelRepository $exerciseModelRepository
     */
    public function setExerciseModelRepository($exerciseModelRepository)
    {
        $this->exerciseModelRepository = $exerciseModelRepository;
    }

    /**
     * Set ownerExerciseModelService
     *
     * @param OwnerExerciseModelService $ownerExerciseModelService
     */
    public function setOwnerExerciseModelService($ownerExerciseModelService)
    {
        $this->ownerExerciseModelService = $ownerExerciseModelService;
    }

    /**
     * Set requiredResourceByExerciseModelRepository
     *
     * @param RequiredResourceByExerciseModelRepository $requiredResourceByExerciseModelRepository
     */
    public function setRequiredResourceByExerciseModelRepository(
        $requiredResourceByExerciseModelRepository
    )
    {
        $this->requiredResourceByExerciseModelRepository = $requiredResourceByExerciseModelRepository;
    }

    /**
     * @param int   $exerciseModelId   Exercise model id
     * @param array $parameters        Parameters
     *
     * @return ExerciseModelResource
     */
    public function getExerciseModelToEdit($exerciseModelId, array $parameters = array())
    {
        return $this->exerciseModelRepository->findToEdit($exerciseModelId, $parameters);
    }

    /**
     * Save an exercise model
     *
     * @param int                   $exerciseModelId Exercise model id
     * @param ExerciseModelResource $exerciseModel
     * @param array                 $parameters
     *
     * @return ExerciseModelResource
     */
    public function save(
        $exerciseModelId,
        ExerciseModelResource $exerciseModel,
        array $parameters = array()
    )
    {
        return $this->exerciseModelRepository->update(
            $exerciseModelId,
            $exerciseModel,
            $parameters
        );
    }

    /**
     * Save a multiple choice
     *
     * @param       $exerciseModelId
     * @param array $mcArray
     *
     * @return ExerciseModelResource
     */
    public function saveMultipleChoice($exerciseModelId, array $mcArray)
    {
//        throw new \Exception(print_r($mcArray, true));
        $multipleChoice = new MultipleChoice();
        $this->setWordingAndDocuments($mcArray, $multipleChoice);
        $multipleChoice->setShuffleQuestionsOrder($mcArray['shuffle']);
        $this->addQuestionBlocksFromArray($mcArray, $multipleChoice);

        $exerciseModel = new ExerciseModelResource();
        $exerciseModel->setTitle($mcArray['title']);
        $exerciseModel->setType(CommonModel::MULTIPLE_CHOICE);
        $exerciseModel->setContent($multipleChoice);

        return $this->save($exerciseModelId, $exerciseModel);
    }

    /**
     * Save required resources
     *
     * @param       $exerciseModelId
     * @param array $resourceArray
     *
     * @return ExerciseModelResource
     */
    public function saveRequiredResource($exerciseModelId, array $resourceArray)
    {
        $requiredResources = array();
        if (isset($resourceArray['requirement'])) {
            foreach ($resourceArray['requirement'] as $requirement) {
                $requiredResources[] = $requirement;
            }
        }

        return $this->requiredResourceByExerciseModelRepository->update(
            $exerciseModelId,
            new ArrayCollection($requiredResources)
        );
    }

    /**
     * Add an exercise model
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return ExerciseModelResource
     */
    public function addFromType(ExerciseModelResource $exerciseModel)
    {
        $content = null;
        switch ($exerciseModel->getType()) {
            case CommonModel::MULTIPLE_CHOICE:
                $content = new MultipleChoice();
                break;
            case CommonModel::GROUP_ITEMS:
                $content = new GroupItems();
                break;
            case CommonModel::ORDER_ITEMS:
                $content = new OrderItems();
                break;
            case CommonModel::PAIR_ITEMS:
                $content = new PairItems();
                break;
        }

        $content->setWording("Consigne");

        $exerciseModel->setContent($content);
        $exerciseModel->setRequiredExerciseResources(array());
        $exerciseModel->setTitle('Titre du modèle d\'exercice');
        $exerciseModel = $this->add($exerciseModel);

        $this->ownerExerciseModelService->addBasicFromExerciseModel($exerciseModel->getId());

        return $exerciseModel;
    }

    /**
     * Insert a new exercise model
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return ExerciseModelResource
     */
    public function add(ExerciseModelResource $exerciseModel)
    {
        return $this->exerciseModelRepository->insert($exerciseModel);
    }

    /**
     * Get an exercise model
     *
     * @param int $exerciseModelId Resource id
     *
     * @return ExerciseModelResource
     */
    public function getToEdit($exerciseModelId)
    {
        return $this->exerciseModelRepository->findToEdit($exerciseModelId);
    }

    /**
     * Get an exercise model
     *
     * @param int $exerciseModelId Resource id
     *
     * @return ExerciseModelResource
     */
    public function get($exerciseModelId)
    {
        return $this->exerciseModelRepository->find($exerciseModelId);
    }

    /**
     * Delete an exercise model
     *
     * @param $exerciseModelId
     */
    public function delete($exerciseModelId)
    {
        $this->exerciseModelRepository->delete($exerciseModelId);
    }

    /**
     * Set the documents and the wording of a model of exercise
     *
     * @param array       $modelArray
     * @param CommonModel $model
     */
    private function setWordingAndDocuments(array $modelArray, CommonModel &$model)
    {
        $model->setWording($modelArray['wording']);

        $documents = array();
        foreach ($modelArray['documents'] as $docId) {
            $document = new ModelDocument();
            $document->setId($docId);
            $documents[] = $document;
        }
        $model->setDocuments($documents);
    }

    /**
     * Create the question blocks from the modelArray
     *
     * @param array          $modelArray
     * @param MultipleChoice $model
     *
     * @throws \Exception
     */
    private function addQuestionBlocksFromArray(array $modelArray, &$model)
    {
        $questionBlocks = array();

        foreach ($modelArray['blocks'] as $key => $blockArray) {
            $block = new QuestionBlock(
                $blockArray['numberOfOccurences'],
                $blockArray['maxNumberOfPropositions'],
                $blockArray['maxNumberOfRightPropositions']
            );

            if ($blockArray['resourceOrigin'] === "list") {
                $resourceList = array();
                foreach ($modelArray['resources'][$key] as $resId) {
                    $resource = new ObjectId();
                    $resource->setId($resId);
                    $resourceList[] = $resource;
                }
                $block->setResources($resourceList);
            } elseif ($blockArray['resourceOrigin'] === "constraints") {
                $objConstraint = new ObjectConstraints();
                $objConstraint->setType(CommonModel::MULTIPLE_CHOICE);

                $mdConstraints = array();
                foreach ($modelArray['key'][$key] as $constrKey => $metaKey) {
                    $mdConstraint = new MetadataConstraint();
                    $mdConstraint->setKey($metaKey);
                    switch ($modelArray['comparator'][$key][$constrKey]) {
                        case MetadataConstraint::IN:
                            if (count($modelArray['values'][$key][$constrKey]) == 0) {
                                throw new \Exception('Invalid value list');
                            }
                            $mdConstraint->setValueIn($modelArray['values'][$key][$constrKey]);
                            break;
                        case MetadataConstraint::BETWEEN:
                            if (count($modelArray['values'][$key][$constrKey]) != 2) {
                                throw new \Exception('Invalid value list');
                            }
                            $keys = array_keys($modelArray['values'][$key][$constrKey]);
                            $mdConstraint->setBetween
                                (
                                    $modelArray['values'][$key][$constrKey][$keys[0]],
                                    $modelArray['values'][$key][$constrKey][$keys[1]]
                                );
                            break;
                        case MetadataConstraint::GREATER_THAN:
                        case MetadataConstraint::GREATER_THAN_OR_EQUALS:
                        case MetadataConstraint::LOWER_THAN:
                        case MetadataConstraint::LOWER_THAN_OR_EQUALS:
                            if (count($modelArray['values'][$key][$constrKey]) != 1) {
                                throw new \Exception('Invalid value list');
                            }
                            $keys = array_keys($modelArray['values'][$key][$constrKey]);
                            $mdConstraint->setComparison
                                (
                                    $modelArray['comparator'][$key][$constrKey],
                                    $modelArray['values'][$key][$constrKey][$keys[0]]
                                );
                            break;
                        case MetadataConstraint::EXISTS:
                            if (count($modelArray['values'][$key][$constrKey]) > 0) {
                                throw new \Exception('Invalid value list');
                            }
                            $mdConstraint->setExists();
                            break;
                    }
                    $mdConstraints[] = $mdConstraint;
                    $objConstraint->setMetadataConstraints($mdConstraints);
                    $block->setResourceConstraint($objConstraint);
                }
                $objConstraint->setMetadataConstraints($mdConstraints);
            } else {
                throw new \Exception('Invalid request: multiple choice model creation');
            }

            $questionBlocks[] = $block;
        }

        $model->setQuestionBlocks($questionBlocks);
    }
}
