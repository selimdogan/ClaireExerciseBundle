<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\MultipleChoice;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Exercise\Common\CommonExercise;

/**
 * The exercise (final) version of the multiple choice with the final questions
 * in the final order.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Exercise extends CommonExercise
{
    /**
     * @var array $questions An array of Question
     * @Serializer\Exclude
     */
    private $questions = array();

    /**
     * Shuffle the order of the questions
     */
    public function shuffleQuestionOrder()
    {
        shuffle($this->questions);
    }

    /**
     * Shuffle the order of the propositions inside of the questions
     */
    public function shufflePropositionOrder()
    {
        foreach ($this->questions as $question) {
            /** @var Question $question */
            $question->shufflePropositionsOrder();
        }
    }

    /**
     * Get question at index $key
     *
     * @param int $key The index
     *
     * @return Question The question
     */
    public function getQuestion($key)
    {
        return $this->questions[$key];
    }

    /**
     * Get questions
     *
     * @return array An array of ExerciseQuestion
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add a question
     *
     * @param Question $question
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }

    /**
     * Get the number of questions in the multiple choice exercise
     *
     * @return int The number of questions
     */
    public function getNumberOfQuestions()
    {
        return count($this->questions);
    }

    /**
     * Compute the itemCount
     */
    public function finalize()
    {
        $this->itemCount = count($this->questions);
    }
}
