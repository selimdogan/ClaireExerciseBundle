<?php
namespace SimpleIT\ClaireExerciseBundle\Serializer\Handler;

use JMS\Serializer\GenericSerializationVisitor;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;

/**
 * Custom Handler for abstract class in exercise serialization
 */
class AbstractClassForExerciseHandler implements SubscribingHandlerInterface
{
    /**
     * Get the subscribing methods
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = array();
        foreach (array(
                     'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource',
                     'SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonExercise',
                     'SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonItem',
                     'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel',
                     'SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\ResourceBlock',
                     'SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject\ExerciseObject',
                     'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\SequenceElement',
                     'SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\CommonKnowledge',
                 ) as $class) {
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => $class,
                'method'    => 'serializeAbstract',
            );
        }

        return $methods;
    }

    /**
     * Serialize an abstract class
     *
     * @param GenericSerializationVisitor $visitor
     * @param mixed                       $object
     * @param array                       $type
     * @param SerializationContext        $context
     *
     * @return mixed
     */
    public function serializeAbstract(
        GenericSerializationVisitor $visitor,
        $object,
        array $type,
        SerializationContext $context
    )
    {
        return $visitor->getNavigator()->accept(clone($object), null, $context);
    }
}
