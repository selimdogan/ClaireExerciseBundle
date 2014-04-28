<?php


namespace SimpleIT\ExerciseBundle\Exception;

/**
 * Class InvalidExerciseResourceException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class InvalidExerciseResourceException extends \Exception
{
    protected $message = 'Invalid resource';

    protected $code = 400;
}
