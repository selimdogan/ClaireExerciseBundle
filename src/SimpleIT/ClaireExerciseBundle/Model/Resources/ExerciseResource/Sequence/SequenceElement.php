<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Validable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SequenceElement
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "object_type", map = {
 *    "block": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock",
 *    "resource_id": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\ResourceId",
 *    "text": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\Text",
 *    "text_fragment": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\TextFragment"
 * })
 */
abstract class SequenceElement implements Validable
{
    /**
     * @const BLOCK_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock'
     */
    const BLOCK_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\SequenceBlock';

    /**
     * @const RESOURCE_ID_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\ResourceId'
     */
    const RESOURCE_ID_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\ResourceId';

    /**
     * @const TEXT_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\Text'
     */
    const TEXT_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\Text';

    /**
     * @const TEXT_FRAGMENT_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\TextFragment'
     */
    const TEXT_FRAGMENT_CLASS = 'SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\Sequence\TextFragment';
}
