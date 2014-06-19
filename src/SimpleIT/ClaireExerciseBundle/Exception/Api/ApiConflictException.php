<?php

namespace SimpleIT\ClaireExerciseBundle\Exception\Api;

/**
 * Class ApiConflictException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiConflictException extends ApiException
{
    /**
     * Constructor
     *
     * @param string $message Message
     */
    public function __construct($message = null)
    {
        parent::__construct(ApiException::STATUS_CODE_CONFLICT, $message);
    }
}
