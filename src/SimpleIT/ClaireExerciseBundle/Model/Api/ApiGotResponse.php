<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Api;

/**
 * Class ApiGotResponse
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiGotResponse extends ApiResponse
{

    /**
     * Constructor
     *
     * @param mixed $resource Resource
     * @param array $groups   Groups that determine allowed fields
     *
     * @codeCoverageIgnore
     */
    public function __construct($resource, array $groups = array())
    {
        parent::__construct($resource, ApiResponse::STATUS_CODE_OK, $groups);
    }
}
