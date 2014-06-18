<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ClaireExerciseBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;

/**
 * Class AnswerResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class AnswerResourceFactory
{

    /**
     * Create an AnswerResource collection
     *
     * @param array $answers
     *
     * @return array
     */
    public static function createCollection(array $answers)
    {
        $answerResources = array();
        foreach ($answers as $answer) {
            $answerResources[] = self::create($answer);
        }

        return $answerResources;
    }

    /**
     * Create an AnswerResource
     *
     * @param Answer $answer
     *
     * @return AnswerResource
     */
    public static function create(Answer $answer)
    {
        $class = 'SimpleIT\ClaireExerciseBundle\Model\Resources\AnswerResource';
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new AbstractClassForExerciseHandler());
                }
            )
            ->build();
        $answerResource = $serializer->deserialize($answer->getContent(), $class, 'json');

        $answerResource->setId($answer->getId());

        return $answerResource;
    }
}
