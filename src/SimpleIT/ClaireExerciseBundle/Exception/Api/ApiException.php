<?php

namespace SimpleIT\ClaireExerciseBundle\Exception\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiException extends HttpException
{

    /*
     * CLIENT ERRORS
     */
    /**
     * BAD REQUEST = 400
     */
    const STATUS_CODE_BAD_REQUEST = 400;

    /**
     * UNAUTHORIZED = 401
     */
    const STATUS_CODE_UNAUTHORIZED = 401;

    /**
     * ACCESS DENIED = 403
     */
    const STATUS_CODE_ACCESS_DENIED = 403;

    /**
     *
     */
    const STATUS_CODE_NOT_FOUND = 404;

    /**
     * METHOD NOT ALLOWED
     */
    const STATUS_CODE_METHOD_NOT_ALLOWED = 405;

    /**
     * NOT ACCEPTABLE
     */
    const STATUS_CODE_NOT_ACCEPTABLE = 406;

    /**
     * CONFLICT
     */
    const STATUS_CODE_CONFLICT = 409;

    /**
     * REQUEST ENTITY TOO LARGE
     */
    const STATUS_CODE_REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * UNPROCESSABLE_ENTITY
     */
    const STATUS_CODE_UNPROCESSABLE_ENTITY = 422;

    /*
     * SERVER ERRORS
     */

    /**
     * INTERNAL SERVER ERROR = 500
     */
    const STATUS_CODE_INTERNAL_SERVER_ERROR = 500;

    /**
     * SERVICE UNVAILABLE = 503
     */
    const STATUS_CODE_SERVICE_UNVAILABLE = 503;

    // @codingStandardsIgnoreStart

    /**
     * @var array List of all the Client error codes
     */
    public static $statusCodesClientError = array(
        400,
        401,
        403,
        404,
        405,
        406,
        409,
        413,
        416,
        422
    );

    /**
     * @var array List of all the Server error codes
     */
    public static $statusCodesServerError = array(500, 503);

    // @codingStandardsIgnoreEnd

}
