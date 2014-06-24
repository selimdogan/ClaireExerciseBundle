<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Api;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiResponse
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiResponse extends Response
{
    /* SUCCESSFUL */
    /**
     *  OK = 200
     */
    const STATUS_CODE_OK = 200;

    /**
     * CREATED = 201
     */
    const STATUS_CODE_CREATED = 201;

    /**
     * NO CONTENT = 204
     */
    const STATUS_CODE_NO_CONTENT = 204;

    /**
     * PARTIAL CONTENT = 206
     */
    const STATUS_CODE_PARTIAL_CONTENT = 206;

    const STATUS_CODE_CONTENT_NOT_FOUND = 404;

    /**
     * @var array
     */
    public static $statusCodeSuccessful = array(200, 201, 202, 204, 206);

    /**
     * @var mixed The returned resource
     */
    private $resource;

    /**
     * @var array
     */
    private $groups;

    /**
     * Constructor
     *
     * @param mixed   $resource   Resource
     * @param integer $statusCode Http status code
     * @param array   $groups     Groups for allowed fields
     * @param array   $headers    Headers of the response
     */
    public function __construct(
        $resource,
        $statusCode = self::STATUS_CODE_OK,
        array $groups = array(),
        array $headers = array()
    )
    {
        $this->resource = $resource;
        $this->groups = $groups;
        parent::__construct('', $statusCode, $headers);
    }

    /**
     * Get resource
     *
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
