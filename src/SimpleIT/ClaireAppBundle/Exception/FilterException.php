<?php

namespace SimpleIT\ExerciseBundle\Exception;

/**
 * Exception thrown when a filter is erroneous in a collection information
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class FilterException extends \Exception
{
    protected $message = 'Invalid filter';
}
