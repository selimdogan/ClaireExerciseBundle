<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\ClassificationConstraints;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\Group;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\Model as GroupItems;
use
    SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\ObjectBlock as GroupItemsObjectBlock;

use
    SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\ObjectBlock as OrderItemsObjectBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\Model as MultipleChoice;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\QuestionBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\Model as OrderItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\SequenceBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems\Model as PairItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems\PairBlock;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\MetadataConstraint;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ModelDocument;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectConstraints;
use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectId;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModel\ExerciseModelRepository;
use
    SimpleIT\ClaireAppBundle\Repository\Exercise\ExerciseModel\RequiredResourceByExerciseModelRepository;

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
        $multipleChoice = new MultipleChoice();
        $this->setWordingAndDocuments($mcArray, $multipleChoice);
        $multipleChoice->setShuffleQuestionsOrder($mcArray['shuffle']);
        $this->addMultipleChoiceQuestionBlocksFromArray($mcArray, $multipleChoice);

        $exerciseModel = new ExerciseModelResource();
        $exerciseModel->setTitle($mcArray['title']);
        $exerciseModel->setType(CommonModel::MULTIPLE_CHOICE);
        $exerciseModel->setContent($multipleChoice);

        return $this->save($exerciseModelId, $exerciseModel);
    }

    /**
     * Save a group items
     *
     * @param int   $exerciseModelId
     * @param array $giArray
     *
     * @throws \Exception
     * @return ExerciseModelResource
     */
    public function saveGroupItems($exerciseModelId, array $giArray)
    {
        $groupItems = new GroupItems();
        $this->setWordingAndDocuments($giArray, $groupItems);
        switch ($giArray['displayGroupNames']) {
            case "ask":
            case "show":
            case "hide":
                $groupItems->setDisplayGroupNames($giArray['displayGroupNames']);
                break;
            default:
                throw new \Exception('Invalid display group names value');
        }

        $localGroups = true;
        if (isset($giArray['group']['root']) && !empty($giArray['group']['root'][0])) {
            $localGroups = false;
            $groupItems->setClassifConstr($this->createClassifConstraints($giArray, 'root'));
        }

        $this->addGroupItemsObjectBlocksFromArray($giArray, $groupItems, $localGroups);

        $exerciseModel = new ExerciseModelResource();
        $exerciseModel->setContent($groupItems);

        return $this->save($exerciseModelId, $exerciseModel);
    }

    /**
     * Save an order items
     *
     * @param int   $exerciseModelId
     * @param array $oiArray
     *
     * @throws \Exception
     * @return ExerciseModelResource
     */
    public function saveOrderItems($exerciseModelId, array $oiArray)
    {
        $orderItems = new OrderItems();
        $this->setWordingAndDocuments($oiArray, $orderItems);

        $orderItems->setGiveFirst(isset($oiArray['give-first']) && $oiArray['give-first'] === "1");
        $orderItems->setGiveLast(isset($oiArray['give-last']) && $oiArray['give-last'] === "1");

        if ($oiArray['sequence-origin'] == 'sequence-resource') {
            $sequenceBlock = new SequenceBlock();
            $sequenceBlock->setKeepAll($oiArray['keepAll']);
            $sequenceBlock->setNumberOfParts($oiArray['number-of-parts']);
            $sequenceBlock->setUseFirst($oiArray['use-first'] === "1");
            $sequenceBlock->setUseLast($oiArray['use-last'] === "1");
            $this->setResourceOrigin(
                $sequenceBlock,
                $oiArray['sequence-blocks'][0],
                $oiArray,
                0,
                CommonResource::SEQUENCE,
                'sequence-resources',
                'sequence-key',
                'sequence-comparator',
                'sequence-values'
            );
            $orderItems->setSequenceBlock($sequenceBlock);
        } elseif ($oiArray['sequence-origin'] == 'resource-list') {
            if ($oiArray['order'] == "desc") {
                $orderItems->setOrder(OrderItems::DESCENDENT);
            } else {
                $orderItems->setOrder(OrderItems::ASCENDENT);
            }
            $orderItems->setShowValues(
                isset($oiArray['show-values']) && $oiArray['show-values'] === "1"
            );
            $this->addOrderItemsObjectBlocksFromArray($oiArray, $orderItems);
        } else {
            throw new \Exception('order items: a content must be chosen');
        }

        $exerciseModel = new ExerciseModelResource();
        $exerciseModel->setContent($orderItems);

        return $this->save($exerciseModelId, $exerciseModel);
    }

    /**
     * Save an order items
     *
     * @param int   $exerciseModelId
     * @param array $piArray
     *
     * @throws \Exception
     * @return ExerciseModelResource
     */
    public function savePairItems($exerciseModelId, array $piArray)
    {
        $orderItems = new PairItems();
        $this->setWordingAndDocuments($piArray, $orderItems);

        $this->addPairItemsObjectBlocksFromArray($piArray, $orderItems);

        $exerciseModel = new ExerciseModelResource();
        $exerciseModel->setContent($orderItems);

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
            if (!empty($docId)) {
                $document = new ModelDocument();
                $document->setId($docId);
                $documents[] = $document;
            }
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
    private function addMultipleChoiceQuestionBlocksFromArray(array $modelArray, &$model)
    {
        $questionBlocks = array();

        foreach ($modelArray['blocks'] as $key => $blockArray) {
            $block = new QuestionBlock(
                $blockArray['numberOfOccurences'],
                $blockArray['maxNumberOfPropositions'],
                $blockArray['maxNumberOfRightPropositions']
            );

            $this->setResourceOrigin(
                $block,
                $blockArray,
                $modelArray,
                $key,
                CommonModel::MULTIPLE_CHOICE
            );

            $questionBlocks[] = $block;
        }

        $model->setQuestionBlocks($questionBlocks);
    }

    /**
     * Create the object blocks from the modelArray
     *
     * @param array      $modelArray
     * @param GroupItems $model
     * @param boolean    $localGroups
     */
    private function addGroupItemsObjectBlocksFromArray(array $modelArray, &$model, $localGroups)
    {
        $objectBlocks = array();

        foreach ($modelArray['blocks'] as $blockId => $blockArray) {
            $block = new GroupItemsObjectBlock($blockArray['numberOfOccurences']);

            if ($localGroups) {
                $block->setClassifConstr($this->createClassifConstraints($modelArray, $blockId));
            }

            $this->setResourceOrigin(
                $block,
                $blockArray,
                $modelArray,
                $blockId
            );

            $objectBlocks[] = $block;
        }

        $model->setObjectBlocks($objectBlocks);
    }

    /**
     * Create the object blocks from the modelArray
     *
     * @param array     $modelArray
     * @param PairItems $model
     */
    private function addPairItemsObjectBlocksFromArray(array $modelArray, &$model)
    {
        $objectBlocks = array();

        foreach ($modelArray['blocks'] as $blockId => $blockArray) {
            $block = new PairBlock(
                $blockArray['numberOfOccurences'],
                $blockArray['metaKey']
            );

            $this->setResourceOrigin(
                $block,
                $blockArray,
                $modelArray,
                $blockId
            );

            $objectBlocks[] = $block;
        }

        $model->setPairBlocks($objectBlocks);
    }

    /**
     * Create the object blocks from the modelArray
     *
     * @param array      $modelArray
     * @param OrderItems $model
     */
    private function addOrderItemsObjectBlocksFromArray(array $modelArray, &$model)
    {
        $objectBlocks = array();

        foreach ($modelArray['blocks'] as $blockId => $blockArray) {
            $block = new OrderItemsObjectBlock(
                $blockArray['numberOfOccurences'],
                $blockArray['metaKey']
            );

            $this->setResourceOrigin(
                $block,
                $blockArray,
                $modelArray,
                $blockId
            );

            $objectBlocks[] = $block;
        }

        $model->setObjectBlocks($objectBlocks);
    }

    /**
     * Set the resource list or resource constraints in a resource block
     *
     * @param ResourceBlock $block          The block object
     * @param array         $blockArray     The block array
     * @param array         $modelArray     The array of the model
     * @param int|string    $blockId        The id or name of the block
     * @param string        $type
     * @param string        $resourcesName  The name of the field resources in the array
     * @param string        $keyName        The name of the field key in the array
     * @param string        $comparatorName The name of the field comparator in the array
     * @param string        $valuesName     The name of the field values in the array
     * @param string        $typeName       The name of the field type in the array
     *
     * @throws \Exception
     */
    private function setResourceOrigin(
        ResourceBlock &$block,
        array $blockArray,
        array $modelArray,
        $blockId,
        $type = null,
        $resourcesName = 'resources',
        $keyName = 'key',
        $comparatorName = 'comparator',
        $valuesName = 'values',
        $typeName = 'type'
    )
    {
        if ($blockArray['resourceOrigin'] === "list") {
            $resourceList = array();
            foreach ($modelArray[$resourcesName][$blockId] as $resId) {
                $resource = new ObjectId();
                $resource->setId($resId);
                $resourceList[] = $resource;
            }
            $block->setResources($resourceList);
        } elseif ($blockArray['resourceOrigin'] === "constraints") {
            $objConstraint = new ObjectConstraints();
            if ($type === null) {
                $type = $modelArray[$typeName][$blockId];
            }
            $objConstraint->setType($type);

            $mdConstraints = array();
            foreach ($modelArray[$keyName][$blockId] as $constrKey => $metaKey) {
                $mdConstraints[] = $this->createMdConstraint(
                    $metaKey,
                    $modelArray[$comparatorName][$blockId][$constrKey],
                    $modelArray[$valuesName][$blockId][$constrKey]
                );
            }
            $objConstraint->setMetadataConstraints($mdConstraints);
            $block->setResourceConstraint($objConstraint);
        } else {
            throw new \Exception('Invalid request: resource origin');
        }
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
        $exerciseModel->setTitle('Titre du modèle d\'exercices');
        $exerciseModel = $this->add($exerciseModel);

        $this->ownerExerciseModelService->addBasicFromExerciseModel($exerciseModel->getId());

        return $exerciseModel;
    }

    /**
     * Create classification constraints from the arrays
     *
     * @param array $giArray
     * @param       $blockId
     *
     * @return ClassificationConstraints
     * @throws \Exception
     */
    private function createClassifConstraints(array $giArray, $blockId)
    {
        $classificationConstraints = new ClassificationConstraints();
        switch ($giArray['other'][$blockId]) {
            case 'own':
            case 'reject':
            case 'misc':
                $classificationConstraints->setOther($giArray['other'][$blockId]);
                break;
            default:
                throw new \Exception('Invalid other value');
        }

        $groups = array();
        $metaKeys = array();
        foreach ($giArray['group'][$blockId] as $groupId => $groupName) {
            $group = new Group();
            $group->setName($groupName);
            $mdConstraints = array();
            foreach ($giArray['classifKey'][$blockId][$groupId] as $constraintId => $key) {
                if (isset($giArray['classifValues'][$blockId][$groupId][$constraintId])) {
                    $classificationValues =
                        $giArray['classifValues'][$blockId][$groupId][$constraintId];
                } else {
                    $classificationValues = array();
                }

                $mdConstraints[] = $this->createMdConstraint(
                    $key,
                    $giArray['classifComparator'][$blockId][$groupId][$constraintId],
                    $classificationValues
                );
                $group->setMDConstraints($mdConstraints);

                if (array_search($key, $metaKeys, true) === false) {
                    $metaKeys[] = $key;
                }
            }
            $groups[] = $group;
        }

        $classificationConstraints->setGroups($groups);
        $classificationConstraints->setMetaKeys($metaKeys);

        return $classificationConstraints;
    }

    /**
     * Create a mdConstraint object from arrays
     *
     * @param $metaKey
     * @param $comparator
     * @param $values
     *
     * @return MetadataConstraint
     * @throws \Exception
     */
    private function createMdConstraint($metaKey, $comparator, $values)
    {
        $mdConstraint = new MetadataConstraint();
        $mdConstraint->setKey($metaKey);
        switch ($comparator) {
            case MetadataConstraint::IN:
                if (count($values) == 0) {
                    throw new \Exception('Invalid value list');
                }
                $mdConstraint->setValueIn($values);
                break;
            case MetadataConstraint::BETWEEN:
                if (count($values) != 2) {
                    throw new \Exception('Invalid value list');
                }
                $keys = array_keys($values);
                $mdConstraint->setBetween
                    (
                        $values[$keys[0]],
                        $values[$keys[1]]
                    );
                break;
            case MetadataConstraint::GREATER_THAN:
            case MetadataConstraint::GREATER_THAN_OR_EQUALS:
            case MetadataConstraint::LOWER_THAN:
            case MetadataConstraint::LOWER_THAN_OR_EQUALS:
                if (count($values) != 1) {
                    throw new \Exception('Invalid value list');
                }
                $keys = array_keys($values);
                $mdConstraint->setComparison
                    (
                        $comparator,
                        $values[$keys[0]]
                    );
                break;
            case MetadataConstraint::EXISTS:
                if (count($values) > 0) {
                    throw new \Exception('Invalid value list');
                }
                $mdConstraint->setExists();
                break;
            default :
                throw new \Exception('Invalid comparator');
        }

        return $mdConstraint;
    }
}
