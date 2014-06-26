<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;

/**
 * Exception thrown when an answer has an invalid format
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MissingIdException extends \Exception
{
    protected $message = 'The id is missing';
}
