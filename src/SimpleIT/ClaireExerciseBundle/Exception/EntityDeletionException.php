<?php

namespace SimpleIT\ClaireExerciseBundle\Exception;

/**
 * Exception thrown when an answer has an invalid format
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class EntityDeletionException extends \Exception
{
    protected $message = 'Impossible to find or to delete the entity';
}
