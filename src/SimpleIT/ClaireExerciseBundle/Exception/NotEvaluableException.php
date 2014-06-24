<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;

/**
 * Exception thrown when an expression cannot be evaluated
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class NotEvaluableException extends \Exception
{
    protected $message = 'The expression cannot be evaluated';
}
