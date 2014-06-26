<?php
namespace SimpleIT\ApiResourcesBundle\Exception;

/**
 * Class InvalidKnowledgeException
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class InvalidKnowledgeException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $message Exception message
     * @param int    $code    Exception code
     */
    public function __construct($message, $code = 500)
    {
        parent::__construct($message, $code);
    }
}
