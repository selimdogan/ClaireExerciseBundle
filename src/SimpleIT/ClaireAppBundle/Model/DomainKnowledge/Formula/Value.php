<?php

namespace SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula;

/**
 * Class Value
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Value extends Expression
{
    /**
     * @const EXPR_NAME = 'value'
     */
    const EXPR_NAME = 'value';

    /**
     * @var int|float
     */
    private $value;

    /**
     * Evaluate the result of the operation with the values specified in parameters for the
     * variables.
     *
     * @param array $parameters
     *
     * @return int|float
     */
    public function evaluate(array $parameters = array())
    {
        return $this->value;
    }

    /**
     * Sets the values of the variables
     *
     * @param array $parameters
     */
    public function valuate(array $parameters)
    {
    }

    /**
     * Return if the expression contains one of the specified variables
     *
     * @param array $varNames
     *
     * @return bool
     */
    public function containsOneOfVariables(array $varNames)
    {
        return false;
    }

    /**
     * Checks if exactly one branch of the expression contains the variable
     *
     * @param $varName
     *
     * @return int
     */
    public function countBranchWithVariable($varName)
    {
        return 0;
    }

    /**
     * Set value
     *
     * @param float|int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the expression name
     *
     * @return string
     */
    public function getExprName()
    {
        return self::EXPR_NAME;
    }

    /**
     * Distributes the multiplication over the addition
     *
     * @param string $varName
     *
     * @return Expression
     */
    public function distributeMultiplication($varName)
    {
        return $this;
    }

    /**
     * Get a clean version of the expression
     *
     * @return Expression
     */
    public function getClean()
    {
        return $this;
    }
}
