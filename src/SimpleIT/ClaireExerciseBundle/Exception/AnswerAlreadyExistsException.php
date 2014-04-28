<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;

/**
 * Exception thrown when an answer has an invalid format
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerAlreadyExistsException extends \Exception
{
    protected $message = 'The answer already exists for this attempt';
}
