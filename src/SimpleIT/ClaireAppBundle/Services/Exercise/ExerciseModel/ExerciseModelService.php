<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\ExerciseModel;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\GroupItems\Model as GroupItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice\Model as MultipleChoice;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\OrderItems\Model as OrderItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\PairItems\Model as PairItems;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;
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
        $this->getQuestionBlocksFromArray($mcArray, $multipleChoice);

        $exerciseModel = new ExerciseModelResource();
        $exerciseModel->setTitle($mcArray['title']);
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
        // TODO documents
    }

    /**
     * Create the question blocks from the modelArray
     *
     * @param array          $modelArray
     * @param MultipleChoice $model
     */
    private function getQuestionBlocksFromArray(array $modelArray, &$model)
    {
        // TODO mc question blocks
    }
}
