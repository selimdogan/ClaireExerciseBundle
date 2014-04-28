<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\OpenEndedQuestion;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\CommonItem;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\Markable;

/**
 * An ExerciseQuestion is a question of openEndedQuestion exercise in its final version,
 * ready to be used in an exercise.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Question extends CommonItem implements Markable
{
    /**
     * @var string $question The wording of this question
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $question;

    /**
     * @var array $answerFormat
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $answerFormat;

    /**
     * @var array $solutions An array of string
     * @Serializer\Type("array<string>")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $solutions = array();

    /**
     * @var string $answer The answer of the learner
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $answer;

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\Groups({"details", "corrected", "item"})
     */
    private $mark = null;

    /**
     * Check if the Markable has a mark
     *
     * @return boolean
     */
    public function isMarked()
    {
        return !is_null($this->mark);
    }

    /**
     * Get mark
     *
     * @return float
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set mark
     *
     * @param float $mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
    }

    /**
     * Set answer
     *
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set question
     *
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set solutions
     *
     * @param array $solutions
     */
    public function setSolutions($solutions)
    {
        $this->solutions = $solutions;
    }

    /**
     * Get solutions
     *
     * @return array
     */
    public function getSolutions()
    {
        return $this->solutions;
    }

    /**
     * Set answerFormat
     *
     * @param array $answerFormat
     */
    public function setAnswerFormat($answerFormat)
    {
        $this->answerFormat = $answerFormat;
    }

    /**
     * Get answerFormat
     *
     * @return array
     */
    public function getAnswerFormat()
    {
        return $this->answerFormat;
    }
}
