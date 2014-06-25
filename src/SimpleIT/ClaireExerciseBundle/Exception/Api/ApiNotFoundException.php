<?php

namespace SimpleIT\ClaireExerciseBundle\Exception\Api;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ApiNotFoundException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiNotFoundException extends NotFoundHttpException
{
    /**
     *
     */
    const MESSAGE_PATTERN = '%s not found';

    /**
     * Constructor
     *
     * @param string $resourceName Resource name
     * @param string $message      Message
     */
    public function __construct($resourceName = null, $message = null)
    {

        if (is_null($message) && !is_null($resourceName)) {
            $message = sprintf(self::MESSAGE_PATTERN, $resourceName);
        }
        parent::__construct($message);
    }
}
