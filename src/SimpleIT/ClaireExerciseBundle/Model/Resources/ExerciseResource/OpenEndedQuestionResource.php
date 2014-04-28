<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OpenEndedQuestionResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OpenEndedQuestionResource extends CommonResource
{
    /**
     * @var string $question The wording of this question
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "resource_storage", "resource_list", "owner_resource_list"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $question;

    /**
     * @var array $solutions An array of valid solutions
     * @Serializer\Type("array<string>")
     * @Serializer\Groups({"details", "resource_storage"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $solutions = array();

    /**
     * @var string $comment A comment linked with the question which will be displayed after the
     * correction
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "resource_storage"})
     */
    private $comment;

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

    /**
     * Validate the question resource.
     *
     * @throws \LogicException
     */
    public function  validate($param = null)
    {
        if ($this->question === null || $this->question == '') {
            throw new \LogicException('Invalid question');
        }

        if (empty($this->solutions)) {
            throw new \LogicException('A solution is required');
        }
    }
}
