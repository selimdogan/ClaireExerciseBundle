<?php

namespace SimpleIT\ClaireExerciseBundle\Service\Exercise\ExerciseCreation;

use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;
use SimpleIT\ClaireExerciseBundle\Exception\InvalidAnswerException;
use SimpleIT\ClaireExerciseBundle\Model\ExerciseObject\OpenEndedQuestion;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResourceFactory;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\OpenEndedQuestion\Exercise;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\OpenEndedQuestion\Question;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OpenEndedQuestion\Model;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OpenEndedQuestion\QuestionBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;

/**
 * Service which manages OpenEndedQuestion
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OpenEndedQuestionService extends ExerciseCreationService
{
    /**
     * @inheritdoc
     */
    public function generateExerciseFromExerciseModel(
        ExerciseModel $exerciseModel,
        CommonModel $commonModel,
        User $owner
    )
    {
        /** @var Model $commonModel */
        // Generation of the exercise with the model
        $exercise = $this->generateOEQExercise($commonModel, $owner);

        // Transformation of the exercise into entities (StoredExercise and Items)
        return $this->toStoredExercise(
            $exercise,
            $exerciseModel,
            "open-ended-question",
            $exercise->getQuestions()
        );
    }

    /**
     * Generate a open ended question exercise from a model
     *
     * @param Model $model
     * @param User  $owner
     *
     * @return Exercise
     */
    private function generateOEQExercise(Model $model, User $owner)
    {
        // Formulas
        $variables = $this->computeFormulaVariableValues($model->getFormula(), $owner);
        $wording = $this->parseStringWithVariables($model->getWording(), $variables);

        $exercise = new Exercise($wording);

        // Documents
        $this->addDocuments($model, $exercise, $owner);

        // array to collect all the questions to add
        $modelQuestionToAdd = array();

        // get the blocks
        foreach ($model->getQuestionBlocks() as $block) {
            $this->addQuestionsFromBlock($block, $modelQuestionToAdd, $owner);
        }

        // Create and add the exercise questions
        $this->addQuestionsToTheExercise($modelQuestionToAdd, $exercise, $owner);

        // shuffle the order of the questions (if needed) and the propositions
        if ($model->isShuffleQuestionsOrder()) {
            $exercise->shuffleQuestionOrder();
        }

        $exercise->finalize();

        return $exercise;
    }

    /**
     * Add questions from an array of MultipleChoiceExerciseQuestions to the Exercise
     *
     * @param array    $modelQuestionToAdd The array of questions
     * @param Exercise $exercise           The Exercise
     * @param User     $owner
     */
    private function addQuestionsToTheExercise(
        array $modelQuestionToAdd,
        Exercise &$exercise,
        User $owner
    )
    {
        /** @var OpenEndedQuestion $modelQuestion */
        foreach ($modelQuestionToAdd as $modelQuestion) {
            // initialize the variables
            $variables = $variables = $this->computeFormulaVariableValues(
                $modelQuestion->getFormula(),
                $owner
            );

            $exerciseQuestion = new Question();

            $exerciseQuestion->setQuestion(
                $this->parseStringWithVariables($modelQuestion->getQuestion(), $variables)
            );

            $exerciseQuestion->setComment(
                $this->parseStringWithVariables($modelQuestion->getComment(), $variables)
            );

            $exerciseQuestion->setSolutions(
                $this->parseArrayWithVariables($modelQuestion->getSolutions(), $variables)
            );

            $exerciseQuestion->setAnswerFormat(
                $this->formulaService->getValueArrayFormat($exerciseQuestion->getSolutions())
            );

            $exercise->addQuestion($exerciseQuestion);
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

        foreach ($blockQuestions as $question) {
            /** @var OpenEndedQuestion $question */
            $modelQuestionToAdd[] = $question;
        }
    }

    /**
     * Retrieve OpenEndedQuestions from a question block
     *
     * @param QuestionBlock $questionBlock
     * @param User          $owner
     *
     * @return array An array of OpenEndedQuestion
     */
    private function questionsFromBlock(QuestionBlock $questionBlock, User $owner)
    {
        $blockQuestions = array();
        $numOfQuestions = $questionBlock->getNumberOfOccurrences();

        // if the block is a list
        if ($questionBlock->isList()) {
            $this->getObjectsFromList($questionBlock, $numOfQuestions, $blockQuestions, $owner);
        } // if the block is question constraints
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
     * Correct the open ended question
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

        $userAnswer = $la->getContent()['answer'];

        $question->setAnswer($userAnswer);

        $this->mark($question);

        return $this->toCorrectedItemEntity(
            $question,
            'open-ended-question'
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
        if (!in_array($question->getAnswer(), $question->getSolutions(), true)) {
            $mark = 0;
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
        if (!is_string($answer['answer'])) {
            throw new InvalidAnswerException('Invalid number of objects in the answer');
        }
    }
}
