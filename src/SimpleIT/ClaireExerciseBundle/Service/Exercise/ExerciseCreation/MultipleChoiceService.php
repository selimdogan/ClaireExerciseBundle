<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseCreation;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice\Exercise;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice\Proposition;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\MultipleChoice\Question;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\MultipleChoice\Model;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\MultipleChoice\QuestionBlock;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\StoredExercise;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\OwnerExerciseModel;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidAnswerException;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseObject\MultipleChoiceQuestion;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResourceFactory;

/**
 * Service which manages Multiple Choice Exercises.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MultipleChoiceService extends ExerciseCreationService
{
    /**
     * Generate an instance of multiple choice exercise according to the input model.
     *
     * @param OwnerExerciseModel $oem
     * @param CommonModel        $exerciseModel
     * @param User               $owner
     *
     * @return StoredExercise
     */
    public function generateExerciseFromExerciseModel(
        OwnerExerciseModel $oem,
        CommonModel $exerciseModel,
        User $owner
    )
    {
        /** @var Model $exerciseModel */
        // Generation of the exercise with the model
        $exercise = $this->generateMCExercise($exerciseModel, $owner);

        // Transformation of the exercise into entities (StoredExercise and Items)
        return $this->toStoredExercise(
            $exercise,
            $oem,
            "multiple-choice",
            $exercise->getQuestions()
        );
    }

    /**
     * Generate a multiple choice exercise from a model
     *
     * @param Model $model
     * @param User  $owner
     *
     * @return Exercise
     */
    private function generateMCExercise(Model $model, User $owner)
    {
        $exercise = new Exercise($model->getWording());

        // Documents
        $this->addDocuments($model, $exercise, $owner);

        // array to collect all the questions to add
        $modelQuestionToAdd = array();

        // get the blocks
        foreach ($model->getQuestionBlocks() as $block) {
            $this->addQuestionsFromBlock($block, $modelQuestionToAdd, $owner);
        }

        /*
         *  Create and add the exercise questions
         */
        $this->addQuestionsToTheExercise($modelQuestionToAdd, $exercise);

        // shuffle the order of the questions (if needed) and the propositions
        if ($model->isShuffleQuestionsOrder()) {
            $exercise->shuffleQuestionOrder();
        }
        $exercise->shufflePropositionOrder();

        $exercise->finalize();

        return $exercise;
    }

    /**
     * Add questions from an array of MultipleChoiceExerciseQuestions
     * to the Exercise
     *
     * @param array    $modelQuestionToAdd The array of questions
     * @param Exercise $exercise           The MultipleChoiceExercise
     */
    private function addQuestionsToTheExercise(
        array $modelQuestionToAdd,
        Exercise &$exercise
    )
    {
        /** @var MultipleChoiceQuestion $modelQuestion */
        foreach ($modelQuestionToAdd as $modelQuestion) {
            // initialize the exercise
            $exerciseQuestion = new Question();
            $exerciseQuestion->setDoNotShuffle($modelQuestion->getDoNotShuffle());

            $exerciseQuestion->setQuestion($modelQuestion->getQuestion());
            $exerciseQuestion->setComment($modelQuestion->getComment());

            // organise the propositions ids
            $forcedRightId = array();
            $forcedWrongId = array();
            $rightId = array();
            $wrongId = array();

            foreach ($modelQuestion->getPropositions() as $key => $proposition) {
                if ($modelQuestion->getForceUse()[$key] === true) {
                    if ($modelQuestion->getRight()[$key] === true) {
                        $forcedRightId[] = $key;
                    } else {
                        $forcedWrongId[] = $key;
                    }
                } else {
                    if ($modelQuestion->getRight()[$key] === true) {
                        $rightId[] = $key;
                    } else {
                        $wrongId[] = $key;
                    }
                }
            }
            // Determine the number of proposition and right proposition to be added in
            $this->numberOfPropositions(
                $modelQuestion,
                $forcedRightId,
                $forcedWrongId,
                $rightId,
                $wrongId,
                $numberOfRAToAdd,
                $numberOfWAToAdd
            );

            // add the forced propositions
            $propositionIds = array_merge($forcedWrongId, $forcedRightId);

            // add the correct number of right propositions
            for ($ind = 0; $ind < $numberOfRAToAdd; $ind++) {
                $key = array_rand($rightId);
                $propositionIds[] = $rightId[$key];
                unset($rightId[$key]);
            }

            // add the correct number of wrong propositions
            for ($ind = 0; $ind < $numberOfWAToAdd; $ind++) {
                $key = array_rand($wrongId);
                $propositionIds[] = $wrongId[$key];
                unset($wrongId[$key]);
            }

            // add the propositions to the exercise question
            sort($propositionIds);
            foreach ($propositionIds as $propId) {
                $exerciseQuestion->addProposition(
                    $modelQuestion->getRight()[$propId],
                    $modelQuestion->getPropositions()[$propId]
                );
            }

            $exercise->addQuestion($exerciseQuestion);
        }
    }

    /**
     * Compute the number of propositions and right propositions for one question.
     * The result are stored in $numberOfRAToAdd and $numberOfWAToAdd
     *
     * @param MultipleChoiceQuestion $modelQuestion   The question
     * @param array                  $forcedRightId
     * @param array                  $forcedWrongId
     * @param array                  $rightId
     * @param array                  $wrongId
     * @param int                    $numberOfRAToAdd (result) number of right propositions to be added
     * @param int                    $numberOfWAToAdd (result) number of wrong propositions to be added
     */
    private function numberOfPropositions(
        MultipleChoiceQuestion $modelQuestion,
        $forcedRightId,
        $forcedWrongId,
        $rightId,
        $wrongId,
        &$numberOfRAToAdd,
        &$numberOfWAToAdd
    )
    {
        // number of propositions to use
        $numberOfForcedRightPropositions = count($forcedRightId);
        $numberOfForcedWrongPropositions = count($forcedWrongId);;
        $numberOfRightPropositions = count($rightId);
        $numberOfWrongPropositions = count($wrongId);

        //get the max number of propositions parameters
        if ($modelQuestion->getMaxNumberOfPropositions() == 0) {
            $maxNumberOfPropositions = $numberOfRightPropositions + $numberOfWrongPropositions;
        } else {
            $maxNumberOfPropositions = $modelQuestion->getMaxNumberOfPropositions()
                - $numberOfForcedWrongPropositions - $numberOfForcedRightPropositions;
            if ($maxNumberOfPropositions < 0) {
                $maxNumberOfPropositions = 0;
            }
        }

        if ($modelQuestion->getMaxNOfRightPropositions() == 0) {
            $maxNumberOfRightPropositions = $numberOfRightPropositions;
        } else {
            $maxNumberOfRightPropositions = $modelQuestion->getMaxNOfRightPropositions()
                - $numberOfForcedRightPropositions;

            if ($maxNumberOfRightPropositions < 0) {
                $maxNumberOfRightPropositions = 0;
            }
        }

        // determine the real possible max number of propositions
        if ($maxNumberOfPropositions > $numberOfRightPropositions + $numberOfWrongPropositions) {
            $maxNumberOfPropositions = $numberOfRightPropositions + $numberOfWrongPropositions;
        }

        // determine the real possible max number of right propositions
        if ($maxNumberOfRightPropositions > $numberOfRightPropositions) {
            $maxNumberOfRightPropositions = $numberOfRightPropositions;
        }
        if ($maxNumberOfRightPropositions > $maxNumberOfPropositions) {
            $maxNumberOfRightPropositions = $maxNumberOfPropositions;
        }

        // determine the min number of right anwers
        if ($numberOfForcedRightPropositions > 0) {
            $minNumberOfRightPropositions = 0;
        } else {
            $minNumberOfRightPropositions = 1;
        }
        if ($numberOfWrongPropositions + 1 < $maxNumberOfPropositions) {
            $minNumberOfRightPropositions = $maxNumberOfPropositions - $numberOfWrongPropositions;
            if ($minNumberOfRightPropositions > $maxNumberOfRightPropositions) {
                $minNumberOfRightPropositions = $maxNumberOfRightPropositions;
            }
        }

        // number of right proposition (RA) and wrong proposition (WA) to add
        $numberOfRAToAdd = rand($minNumberOfRightPropositions, $maxNumberOfRightPropositions);
        $numberOfWAToAdd = $maxNumberOfPropositions - $numberOfRAToAdd;
        if ($numberOfWAToAdd > $numberOfWrongPropositions) {
            $numberOfWAToAdd = $numberOfWrongPropositions;
        }

    }

    /**
     * Add questions to the question-to-add list from a questionBlock
     *
     * @param QuestionBlock $questionBlock
     * @param array         $modelQuestionToAdd
     * @param User          $owner
     */
    private function addQuestionsFromBlock(
        QuestionBlock $questionBlock,
        &$modelQuestionToAdd,
        User $owner
    )
    {
        // get the questions from the block
        $blockQuestions = $this->questionsFromBlock($questionBlock, $owner);

        // Complete the questions with block information and add it to the list
        foreach ($blockQuestions as $question) {
            /** @var MultipleChoiceQuestion $question */
            // overload the max number of propositions
            if ($questionBlock->getMaxNumberOfPropositions() > 0) {
                $question->setMaxNumberOfPropositions($questionBlock->getMaxNumberOfPropositions());
            }
            if ($questionBlock->getMaxNumberOfRightPropositions() > 0) {
                $question->setMaxNOfRightPropositions(
                    $questionBlock->getMaxNumberOfRightPropositions()
                );
            }

            // add it to the list
            $modelQuestionToAdd[] = $question;
        }
    }

    /**
     * Retrieve MultipleChoiceQuestions from a question block
     *
     * @param QuestionBlock $questionBlock
     * @param User          $owner
     *
     * @return array An array of MultipleChoiceQuestion
     */
    private function questionsFromBlock(QuestionBlock $questionBlock, User $owner)
    {
        $blockQuestions = array();
        $numOfQuestions = $questionBlock->getNumberOfOccurrences();

        /*
         * if the block is a list
         */
        if ($questionBlock->isList()) {
            $this->getObjectsFromList($questionBlock, $numOfQuestions, $blockQuestions, $owner);
        } /*
         * if the block is question constraints
         */
        else {
            $oc = $questionBlock->getResourceConstraint();
            $blockQuestions = $this->exerciseResourceService
                ->getExerciseObjectsFromConstraints(
                    $oc,
                    $numOfQuestions,
                    $owner
                );
        }

        return $blockQuestions;
    }

    /**
     * Correct the multiple choice question
     *
     * @param Item   $item
     * @param Answer $answer
     *
     * @return Item
     */
    public function correct(Item $item, Answer $answer)
    {
        /** @var Question $question */
        $question = $this->getItemFromEntity($item);
        $la = AnswerResourceFactory::create($answer);

        $userTicks = $la->getContent();

        foreach ($userTicks as $key => $tick) {
            $question->setTicked($key, $tick);
        }

        $this->mark($question);

        return $this->toCorrectedItemEntity(
            $question,
            'multiple-choice'
        );
    }

    /**
     * Compute and set mark to the question
     *
     * @param Question $question
     */
    private function mark(Question &$question)
    {
        $mark = 100.0;

        foreach ($question->getPropositions() as $prop) {
            /** @var Proposition $prop */
            if ($prop->getTicked() != $prop->getRight()) {
                $mark = 0.0;
            }
        }

        $question->setMark($mark);
    }

    /**
     * Validate the answer to an item
     *
     * @param Item  $itemEntity
     * @param array $answer
     *
     * @throws InvalidAnswerException
     */
    public function validateAnswer(Item $itemEntity, array $answer)
    {
        /** @var Question $question */
        $question = $this->getItemFromEntity($itemEntity);

        $nbProp = count($question->getPropositions());

        if (count($answer) !== $nbProp) {
            throw new InvalidAnswerException('Invalid number of objects in the answer');
        }

        foreach ($answer as $ans) {
            if ($ans !== 1 && $ans !== 0) {
                throw new InvalidAnswerException('Invalid format for response : 0 or 1 expected.');
            }
        }
    }
}