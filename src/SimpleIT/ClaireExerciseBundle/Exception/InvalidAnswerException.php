<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;

/**
 * Exception thrown when an answer has an invalid format
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class InvalidAnswerException extends \Exception
{
    protected $message = 'Invalid content for the answer';
}
