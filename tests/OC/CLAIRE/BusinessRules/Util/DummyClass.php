<?php

namespace OC\CLAIRE\BusinessRules\Util;

use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DummyClass implements ControllerResolverInterface, LoggerInterface, SerializerInterface
{

    /**
     * Returns the Controller instance associated with a Request.
     *
     * As several resolvers can exist for a single application, a resolver must
     * return false when it is not able to determine the controller.
     *
     * The resolver must only throw an exception when it should be able to load
     * controller but cannot because of some errors made by the developer.
     *
     * @param Request $request A Request instance
     *
     * @return mixed|Boolean A PHP callable representing the Controller,
     *                       or false if this resolver is not able to determine the controller
     *
     * @throws \InvalidArgumentException|\LogicException If the controller can't be found
     *
     * @api
     */
    public function getController(Request $request)
    {
        return null;
    }

    /**
     * Returns the arguments to pass to the controller.
     *
     * @param Request $request    A Request instance
     * @param mixed   $controller A PHP callable
     *
     * @return array An array of arguments to pass to the controller
     *
     * @throws \RuntimeException When value for argument given is not provided
     *
     * @api
     */
    public function getArguments(Request $request, $controller)
    {
        return null;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        return null;
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function alert($message, array $context = array())
    {
        return null;
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function critical($message, array $context = array())
    {
        return null;
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function error($message, array $context = array())
    {
        return null;
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function warning($message, array $context = array())
    {
        return null;
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function notice($message, array $context = array())
    {
        return null;
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function info($message, array $context = array())
    {
        return null;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function debug($message, array $context = array())
    {
        return null;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        return null;
    }

    /**
     * Serializes the given data to the specified output format.
     *
     * @param object|array $data
     * @param string       $format
     * @param Context      $context
     *
     * @return string
     */
    public function serialize($data, $format, SerializationContext $context = null)
    {
        return null;
    }

    /**
     * Deserializes the given data to the specified type.
     *
     * @param string  $data
     * @param string  $type
     * @param string  $format
     * @param Context $context
     *
     * @return object|array
     */
    public function deserialize($data, $type, $format, DeserializationContext $context = null)
    {
 return null;
}}
