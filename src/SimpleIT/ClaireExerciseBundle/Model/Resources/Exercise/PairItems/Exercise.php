<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\PairItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\Common\CommonExercise;

/**
 * Class Exercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Exercise extends CommonExercise
{
    /**
     * @var Item
     * @Serializer\Exclude
     */
    private $item;

    /**
     * Set item
     *
     * @param \SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\PairItems\Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * Get item
     *
     * @return \SimpleIT\ClaireExerciseResourceBundle\Model\Resources\Exercise\PairItems\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Constructor : itemCount = 1 for this type of exercise.
     */
    function __construct($wording)
    {
        parent::__construct($wording);
        $this->itemCount = 1;
    }
}
