<?php

namespace SimpleIT\ExerciseBundle\Service\DomainKnowledge;

use SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Unknown;
use SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Variable as ResourceVariable;
use SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula;
use SimpleIT\ExerciseBundle\Exception\NotEvaluableException;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Addition;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Cos;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Equation;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Inverse;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Multiplication;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Power;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Sin;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Value;
use SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula\Variable;

/**
 * Interface for the service that manages formulas
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface FormulaServiceInterface
{
    /**
     * Check if the syntax of a text equation is correct (brackets, operators, etc.)
     *
     * @param string $equation
     *
     * @return array If no error in the expression
     */
    public function checkTextEquation($equation);

    /**
     * Converts a text expression into an expression tree object
     *
     * @param $expression
     *
     * @return Addition|Cos|Equation|Multiplication|Power|Sin|Value|Variable
     */
    public function textExpressionToExpression($expression);

    /**
     * Resolve an equation for the specified variable
     *
     * @param Equation $equation
     * @param string   $varName
     *
     * @return Equation
     * @throws \Exception
     */
    public function resolveEquation(Equation $equation, $varName);

    /**
     * Instantiate variables from an array of resource variables.
     *
     * @param ResourceVariable[] $resourceVariables The input resource variables
     *
     * @return array An array in wich keys are variable names
     * @throws \SimpleIT\ExerciseBundle\Exception\NotEvaluableException
     */
    public function instantiateVariables(array $resourceVariables);

    /**
     * Resolve a formula and returns values of the variables in an array
     *
     * @param string                                                                   $formula
     * @param \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Variable[] $variables
     * @param Unknown                                                                  $unknown
     *
     * @throws \SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException
     * @return array
     */
    public function resolveFormulaResource($formula, $variables, $unknown);

    /**
     * Validate a formula resource
     *
     * @param Formula $content
     *
     * @return bool
     * @throws \SimpleIT\ApiResourcesBundle\Exception\InvalidKnowledgeException
     */
    public function validateFormulaResource(Formula $content);

    /**
     * Get the format (mask) for a value
     *
     * @param $value
     *
     * @return null|string
     */
    public function getValueFormat($value);

    /**
     * Build an array of (unique) formats for an array of values
     *
     * @param $values
     *
     * @return array|null
     */
    public function getValueArrayFormat($values);
}
