<?php

namespace SimpleIT\ExerciseBundle\Model\ExerciseObject;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\OpenEndedQuestionResource;

/**
 * This class manages the creation of instances of OpenEndedQuestion.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OpenEndedQuestionFactory
{
    /**
     * Create a model question from an exerciseResource
     *
     * @param OpenEndedQuestionResource $res The resource
     *
     * @return OpenEndedQuestion
     */
    public static function createFromCommonResource(OpenEndedQuestionResource $res)
    {
        $oeq = new OpenEndedQuestion();

        $oeq->setComment($res->getComment());
        $oeq->setQuestion($res->getQuestion());
        $oeq->setSolutions($res->getSolutions());

        return $oeq;
    }
}
