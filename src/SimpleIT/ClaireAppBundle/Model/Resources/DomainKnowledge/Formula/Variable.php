<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Variable, is used in a formula
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Variable extends Unknown
{
    /**
     * @var Interval $interval
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Interval")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $interval;

    /**
     * @var array $values
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $values;

    /**
     * @var string $expression
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $expression;

    /**
     * @var array $wrongValues
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $wrongValues;

    /**
     * @var array $wrongExpressions
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $wrongExpressions;

    /**
     * Set expression
     *
     * @param string $expression
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Get expression
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Set interval
     *
     * @param \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Interval $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * Get interval
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Interval
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set values
     *
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * Get values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Set wrongExpressions
     *
     * @param array $wrongExpressions
     */
    public function setWrongExpressions($wrongExpressions)
    {
        $this->wrongExpressions = $wrongExpressions;
    }

    /**
     * Get wrongExpressions
     *
     * @return array
     */
    public function getWrongExpressions()
    {
        return $this->wrongExpressions;
    }

    /**
     * Set wrongValues
     *
     * @param array $wrongValues
     */
    public function setWrongValues($wrongValues)
    {
        $this->wrongValues = $wrongValues;
    }

    /**
     * Get wrongValues
     *
     * @return array
     */
    public function getWrongValues()
    {
        return $this->wrongValues;
    }
}
