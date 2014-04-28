<?php

namespace SimpleIT\ClaireExerciseBundle\Model\ExerciseObject;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseObject\ExerciseObject;

/**
 * Class OpenEndedQuestion
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OpenEndedQuestion extends ExerciseObject
{
    /**
     * @var string $question The wording of the question (text)
     */
    private $question;

    /**
     * @var array The possible solutions
     */
    private $solutions;

    /**
     * @var string $comment The comment of the question to be displayed after the correction
     */
    private $comment = "";

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
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
}
