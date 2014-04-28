<?php

namespace SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula;

use SimpleIT\ExerciseBundle\Exception\NotEvaluableException;

/**
 * Class Variable
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Variable extends Expression
{
    /**
     * @const EXPR_NAME = 'variable'
     */
    const EXPR_NAME = 'variable';

    /**
     * @var string
     */
    private $name;

    /**
     * @var int|float
     */
    private $value = null;

    /**
     * Evaluate the result of the operation with the values specified in parameters for the
     * variables.
     *
     * @param array $parameters
     *
     * @throws NotEvaluableException
     * @return int|float
     */
    public function evaluate(array $parameters = array())
    {
        if (isset($parameters[$this->name])) {
            return $parameters[$this->name];
        } elseif ($this->value !== null) {
            return $this->value;
        } else {
            throw new NotEvaluableException("The variable has no value. It cannot be evaluated");
        }
    }

    /**
     * Sets the values of the variables
     *
     * @param array $parameters
     *
     * @throws \SimpleIT\ExerciseBundle\Exception\NotEvaluableException
     */
    public function valuate(array $parameters)
    {
        if (isset($parameters[$this->name])) {
            $this->value = $parameters[$this->name];
        }
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
        return array_search($this->name, $varNames, true) !== false;
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
        if ($this->containsOneOfVariables(array($varName))) {
            return 1;
        }

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
