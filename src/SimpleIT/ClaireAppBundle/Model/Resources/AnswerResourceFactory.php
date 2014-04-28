<?php
namespace SimpleIT\ExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SimpleIT\ApiBundle\Serializer\Handler\AbstractClassForExerciseHandler;
use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\ExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\Utils\Collection\PaginatorInterface;

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
     * @param PaginatorInterface $answers
     *
     * @return array
     */
    public static function createCollection(PaginatorInterface $answers)
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
        $class = 'SimpleIT\ApiResourcesBundle\Exercise\AnswerResource';
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
