<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\MultipleChoice;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock;

/**
 * Block of questions in a multiple choice model
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class QuestionBlock extends ResourceBlock
{
    /**
     * @var int $maxNumberOfPropositions The max number of propositions in each question
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $maxNumberOfPropositions;

    /**
     * @var int $maxNOfRightPropositions The max number of right propositions in each question
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $maxNumberOfRightPropositions;

    /**
     * QuestionBlock constructor
     *
     * @param int $numberOfOccurrences
     * @param int $maxNumberOfPropositions
     * @param int $maxNOfRightPropositions
     *
     * @return QuestionBlock
     */
    function __construct(
        $numberOfOccurrences,
        $maxNumberOfPropositions = null,
        $maxNOfRightPropositions = null
    )
    {
        $this->numberOfOccurrences = $numberOfOccurrences;
        $this->maxNumberOfPropositions = $maxNumberOfPropositions;
        $this->maxNumberOfRightPropositions = $maxNOfRightPropositions;
    }

    /**
     * Get the maximum number of propositions
     *
     * @return int
     */
    public function getMaxNumberOfPropositions()
    {
        return $this->maxNumberOfPropositions;
    }

    /**
     * Get the maximum number of propositions
     *
     * @param     $maxNumberOfPropositions
     */
    public function setMaxNumberOfPropositions($maxNumberOfPropositions)
    {
        $this->maxNumberOfPropositions = $maxNumberOfPropositions;
    }

    /**
     * get th emaximum number of right propositions
     *
     * @return int
     */
    public function getMaxNumberOfRightPropositions()
    {
        return $this->maxNumberOfRightPropositions;
    }

    /**
     * Set the maximum number of right propositions
     *
     * @param int $maxNOfRightPropositions
     */
    public function setMaxNumberOfRightPropositions($maxNOfRightPropositions)
    {
        $this->maxNumberOfRightPropositions = $maxNOfRightPropositions;
    }
}
