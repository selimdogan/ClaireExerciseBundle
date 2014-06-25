<?php

namespace SimpleIT\ClaireExerciseBundle\Exception\Api;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiBadRequestException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiBadRequestException extends ApiException
{

    /**
     * Constructor
     *
     * @param mixed $errors Errors
     */
    public function __construct($errors)
    {
        $formatedErrors = json_encode($errors);
        parent::__construct(ApiException::STATUS_CODE_BAD_REQUEST, $formatedErrors);
    }
}
