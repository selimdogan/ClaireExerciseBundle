<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Validable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SequenceElement
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "object_type", map = {
 *    "block": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock",
 *    "resource_id": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\ResourceId",
 *    "text": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\Text",
 *    "text_fragment": "SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\TextFragment"
 * })
 */
abstract class SequenceElement implements Validable
{
    /**
     * @const BLOCK_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock'
     */
    const BLOCK_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock';

    /**
     * @const RESOURCE_ID_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\ResourceId'
     */
    const RESOURCE_ID_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\ResourceId';

    /**
     * @const TEXT_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\Text'
     */
    const TEXT_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\Text';

    /**
     * @const TEXT_FRAGMENT_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\TextFragment'
     */
    const TEXT_FRAGMENT_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\Sequence\TextFragment';
}
