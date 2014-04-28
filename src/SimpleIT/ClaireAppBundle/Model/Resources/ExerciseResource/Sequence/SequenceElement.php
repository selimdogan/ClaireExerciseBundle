<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\Validable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SequenceElement
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "object_type", map = {
 *    "block": "SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\SequenceBlock",
 *    "resource_id": "SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\ResourceId",
 *    "text": "SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\Text",
 *    "text_fragment": "SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\TextFragment"
 * })
 */
abstract class SequenceElement implements Validable
{
    /**
     * @const BLOCK_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\SequenceBlock'
     */
    const BLOCK_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\SequenceBlock';

    /**
     * @const RESOURCE_ID_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\ResourceId'
     */
    const RESOURCE_ID_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\ResourceId';

    /**
     * @const TEXT_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\Text'
     */
    const TEXT_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\Text';

    /**
     * @const TEXT_FRAGMENT_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\TextFragment'
     */
    const TEXT_FRAGMENT_CLASS = 'SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\Sequence\TextFragment';
}
