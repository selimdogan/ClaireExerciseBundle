<?php

namespace SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula;

/**
 * Class Expression
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class Expression
{
    /**
     * Evaluate the expression with the values specified in parameters for the variables.
     *
     * @param array $parameters
     *
     * @return int|float
     */
    abstract public function evaluate(array $parameters = array());

    /**
     * Sets the values of the variables
     *
     * @param array $parameters
     */
    abstract public function valuate(array $parameters);

    /**
     * return if the expression contains one of the specified variables
     *
     * @param array $varNames
     *
     * @return bool
     */
    abstract public function containsOneOfVariables(array $varNames);

    /**
     * Get the expression name
     *
     * @return string
     */
    abstract public function getExprName();

    /**
     * Create a new expression object
     *
     * @param $name
     *
     * @return Addition|Cos|Equation|Inverse|Multiplication|Power|Sin|Value|Variable
     * @throws \LogicException
     */
    public static function createOperation($name)
    {
        switch ($name) {
            case Equation::EXPR_NAME:
                return new Equation();
                break;
            case Addition::EXPR_NAME:
                return new Addition();
                break;
            case Multiplication::EXPR_NAME:
                return new Multiplication();
                break;
            case Power::EXPR_NAME:
                return new Power();
                break;
            case Inverse::EXPR_NAME:
                return new Inverse();
                break;
            case Cos::EXPR_NAME:
                return new Cos();
                break;
            case Sin::EXPR_NAME:
                return new Sin();
                break;
            case Value::EXPR_NAME:
                return new Value();
                break;
            case Variable::EXPR_NAME:
                return new Variable();
                break;
            default:
                throw new \LogicException('Unknown type of expression: ' . $name);
        }
    }

    /**
     * Distributes the multiplication over the addition
     *
     * @param string $varName
     *
     * @return Expression
     */
    abstract public function distributeMultiplication($varName);

    /**
     * Checks if exactly one branch of the expression contains the variable
     *
     * @param $varName
     *
     * @return int
     */
    abstract public function countBranchWithVariable($varName);

    /**
     * Get a clean version of the expression
     *
     * @return Expression
     */
    abstract public function getClean();
}
