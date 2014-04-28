<?php

namespace SimpleIT\ExerciseBundle\Model\DomainKnowledge\Formula;

/**
 * Class Addition
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Addition extends Expression
{
    /**
     * @const EXPR_NAME = 'addition'
     */
    const EXPR_NAME = 'addition';

    /**
     * @var array
     */
    private $terms;

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
        $result = 0;
        /** @var Expression $term */
        foreach ($this->terms as $term) {
            $result += $term->evaluate($parameters);
        }

        return $result;
    }

    /**
     * Sets the values of the variables
     *
     * @param array $parameters
     */
    public function valuate(array $parameters)
    {
        /** @var Expression $term */
        foreach ($this->terms as &$term) {
            $term->valuate($parameters);
        }
    }

    /**
     * return if the expression contains one of the specified variables
     *
     * @param array $varNames
     *
     * @return bool
     */
    public function containsOneOfVariables(array $varNames)
    {
        /** @var Expression $term */

        foreach ($this->terms as $term) {
            if ($term->containsOneOfVariables($varNames)) {
                return true;
            }
        }

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
        $count = 0;
        /** @var Expression $term */
        foreach ($this->terms as $term) {
            if ($term->containsOneOfVariables(array($varName))) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Set terms
     *
     * @param array $terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

    /**
     * Add term
     *
     * @param Expression $term
     */
    public function addTerm(Expression $term)
    {
        $this->terms[] = $term;
    }

    /**
     * Get terms
     *
     * @return array
     */
    public function getTerms()
    {
        return $this->terms;
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
        $terms = array();
        /** @var Expression $term */
        foreach ($this->terms as $term) {
            $distributed = $term->distributeMultiplication($varName);

            if ($distributed->getExprName() === self::EXPR_NAME) {
                /** @var Addition $distributed */
                $terms = array_merge($terms, $distributed->getTerms());
            } else {
                $terms[] = $distributed;
            }
        }

        $newAddition = new Addition();
        $newAddition->setTerms($terms);

        return $newAddition;
    }

    /**
     * Get a clean version of the expression
     *
     * @return Expression
     */
    public function getClean()
    {
        $terms = array();

        /** @var Expression $term */
        foreach ($this->terms as $term) {
            // clean each term
            $term = $term->getClean();

            // check if it is a multiplication
            if ($term->getExprName() === Addition::EXPR_NAME) {
                /** @var Addition $term */
                $terms = array_merge($terms, $term->getTerms());
            } else {
                $terms[] = $term;
            }
        }

        $this->terms = $terms;
        $terms = array();
        $value = 0;

        foreach ($this->terms as $term) {
            if ($term->getExprName() === Value::EXPR_NAME) {
                /** @var Value $term */
                $value += $term->getValue();
            } else {
                $terms[] = $term;
            }
        }

        if ($value !== 0) {
            $valExpr = new Value();
            $valExpr->setValue($value);
            $terms[] = $valExpr;
        }

        if (count($terms) === 1) {
            return $terms[0];
        } else {
            $this->terms = $terms;

            return $this;
        }
    }
}
