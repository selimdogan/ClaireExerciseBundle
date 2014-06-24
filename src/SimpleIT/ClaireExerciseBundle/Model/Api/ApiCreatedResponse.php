<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Api;

/**
 * Class ApiCreatedResponse
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiCreatedResponse extends ApiResponse
{
    /**
     * Constructor
     *
     * @param mixed $resource Created resource
     * @param array $groups   Serialization group
     *
     * @codeCoverageIgnore
     */
    public function __construct($resource, $groups = array())
    {
        parent::__construct($resource, ApiResponse::STATUS_CODE_CREATED, $groups);
    }
}
