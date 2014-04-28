<?php

namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\PairItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;

/**

/**
 * Class Model
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Model extends CommonModel
{
    /**
     * @var array $pairBlocks An array of PairBlock
     * @Serializer\Type("array<SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\PairItems\PairBlock>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $pairBlocks = array();

    /**
     * Get pair blocks
     *
     * @return array
     */
    public function getPairBlocks()
    {
        return $this->pairBlocks;
    }

    /**
     * Add a PairBlock to the model
     *
     * @param PairBlock $pairBlock
     */
    public function addPairBlock(PairBlock $pairBlock)
    {
        $this->pairBlocks[] = $pairBlock;
    }

    /**
     * Set pairBlocks
     *
     * @param array $pairBlocks
     */
    public function setPairBlocks($pairBlocks)
    {
        $this->pairBlocks = $pairBlocks;
    }
}
