<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;

/**
 * Class NoAuthorException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class NoAuthorException extends \Exception
{
    protected $message = 'An author must be specified';
}
