<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;


/**
 * Class InvalidModelException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class InvalidModelException extends \Exception
{
    /**
     * @param string                $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
