<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\OpenEndedQuestion;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;

/**
 * A short answer question model. It contains blocks of questions and a parameter to
 * indicate if the question have to be shuffled before formatting the final
 * version of the multiple choice.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Model extends CommonModel
{
    /**
     * @var array $questionBlocks An array of QuestionBlock
     * @Serializer\Type("array<SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\OpenEndedQuestion\QuestionBlock>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $questionBlocks = array();

    /**
     * @var boolean $shuffleQuestionsOrder True if the questions have to be shuffled
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $shuffleQuestionsOrder;

    /**
     * Get the question blocks
     *
     * @return array An aray of QuestionBlock
     */
    public function getQuestionBlocks()
    {
        return $this->questionBlocks;
    }

    /**
     * Set question blocks
     *
     * @param array $questionBlocks An aray of QuestionBlock
     */
    public function setQuestionBlocks($questionBlocks)
    {
        $this->questionBlocks = $questionBlocks;
    }

    /**
     * Add a question block
     *
     * @param QuestionBlock $questionBlock
     */
    public function addQuestionBlock(QuestionBlock $questionBlock)
    {
        $this->questionBlocks[] = $questionBlock;
    }

    /**
     * Get shuffleQuestionsOrder
     *
     * @return boolean
     */
    public function isShuffleQuestionsOrder()
    {
        return $this->shuffleQuestionsOrder;
    }

    /**
     * Set shuffleQuestionsOrder
     *
     * @param boolean $shuffleQuestionsOrder
     */
    public function setShuffleQuestionsOrder($shuffleQuestionsOrder)
    {
        $this->shuffleQuestionsOrder = $shuffleQuestionsOrder;
    }
}
