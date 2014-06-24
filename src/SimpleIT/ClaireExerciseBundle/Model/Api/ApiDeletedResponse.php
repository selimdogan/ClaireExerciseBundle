<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Api;

/**
 * Class ApiDeletedResponse
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiDeletedResponse extends ApiResponse
{
    /**
     * Constructor
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        parent::__construct('', ApiResponse::STATUS_CODE_NO_CONTENT);
    }
}
