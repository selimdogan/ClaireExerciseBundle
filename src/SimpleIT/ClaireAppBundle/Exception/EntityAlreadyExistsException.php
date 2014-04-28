<?php

namespace SimpleIT\ExerciseBundle\Exception;

/**
 * Class EntityAlreadyExistsException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class EntityAlreadyExistsException extends \Exception
{
    /**
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        parent::__construct($entityName . " already exists");
    }
}
