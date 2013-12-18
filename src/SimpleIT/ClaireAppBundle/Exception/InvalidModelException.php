<?php

namespace SimpleIT\ClaireAppBundle\Exception;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseModelResource;

/**
 * Class InvalidModelException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class InvalidModelException extends \Exception
{
    /**
     * @var CommonModel
     */
    private $exerciseModel;

    /**
     * @param string                $message
     * @param ExerciseModelResource $exerciseModel
     */
    public function __construct($message, ExerciseModelResource $exerciseModel)
    {
        parent::__construct($message);
        $this->exerciseModel = $exerciseModel;
    }

    /**
     * Set exerciseModel
     *
     * @param \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel $exerciseModel
     */
    public function setExerciseModel($exerciseModel)
    {
        $this->exerciseModel = $exerciseModel;
    }

    /**
     * Get exerciseModel
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseModel\Common\CommonModel
     */
    public function getExerciseModel()
    {
        return $this->exerciseModel;
    }
}
