<?php
namespace SimpleIT\ClaireAppBundle\Model\Exercise;

use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;

/**
 * Class AnswerResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerResourceFactory
{
    /**
     * Create an answerResource from array of answers
     *
     * @param array $array
     *
     * @return AnswerResource
     */
    public static function create(array $array)
    {
        $answerResource = new AnswerResource();
        $answerResource->setContent($array);
        return $answerResource;
    }
}
