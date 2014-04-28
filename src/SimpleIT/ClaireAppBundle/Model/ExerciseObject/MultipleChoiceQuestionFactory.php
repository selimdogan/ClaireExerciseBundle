<?php

namespace SimpleIT\ExerciseBundle\Model\ExerciseObject;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use
    SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoice\MultipleChoicePropositionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoiceQuestionResource;

/**
 * This class manages the creation of instances of MultipleChoiceQuestion.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MultipleChoiceQuestionFactory
{
    /**
     * Create a model question from an exerciseResource
     *
     * @param MultipleChoiceQuestionResource $res The resource
     *
     * @return MultipleChoiceQuestion
     */
    public static function createFromCommonResource(MultipleChoiceQuestionResource $res)
    {
        $mcq = new MultipleChoiceQuestion();

        $mcq->setComment($res->getComment());
        $mcq->setQuestion($res->getQuestion());
        $mcq->setDoNotShuffle($res->getDoNotShuffle() === true);
        $mcq->setMaxNOfRightPropositions($res->getMaxNumberOfRightPropositions());
        $mcq->setMaxNumberOfPropositions($res->getMaxNumberOfPropositions());

        /** @var MultipleChoicePropositionResource $prop */
        foreach ($res->getPropositions() as $prop) {
            $mcq->addProposition($prop->getText(), $prop->getRight(), $prop->getForceUse());
        }

        return $mcq;
    }
}
