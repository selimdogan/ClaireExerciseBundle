<?php

namespace SimpleIT\ExerciseBundle\Model\ModelObject;

use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\MetadataConstraint;

/**
 * Factory that manages the creation of MetadataConstraint
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataConstraintFactory
{
    /**
     * Create a MetadataConstraint from a metadata-constraint node
     *
     * @param \DOMNode $node
     *
     * @return MetadataConstraint
     * @throws \Exception
     */
    public static function createFromDomNode(\DOMNode $node)
    {
        // Get the key
        $param = $node->attributes
            ->getNamedItem("key")
            ->nodeValue;

        // New MetadataConstraint
        $metadataConstraint = new MetadataConstraint();
        $metadataConstraint->setKey($param);

        // Get constraint list
        $domConstraints = $node->childNodes;

        foreach ($domConstraints as $constr) {
            /*
             *  composed node (between)
             */
            if ($constr->nodeName == "between") {
                $min = null;
                $max = null;

                // Get min and max
                foreach ($constr->childNodes as $minOrMax) {
                    if ($minOrMax->nodeName == "min") {
                        $min = $minOrMax->textContent;
                    } elseif ($minOrMax->nodeName == "max") {
                        $max = $minOrMax->textContent;
                    }
                }

                // validate data
                if ($min == null || $max == null) {
                    throw new \LogicException("Missing between min or max");
                } elseif ($min > $max) {
                    throw new \LogicException("Invalid between : min > max");
                }

                // add to the MetadataConstraint
                $metadataConstraint->setBetween($min, $max);
            }

            /*
             *  empty node (exists)
             */
            if ($constr->nodeName == "exists") {
                $metadataConstraint->setExists();
            } /*
             *  simple value (equals, lt, lte, gt, gte)
             */
            else {
                $nodeTextContent = $constr->textContent;

                // equals
                if ($constr->nodeName == "equals") {
                    $metadataConstraint->addValue($nodeTextContent);
                } // simple comparison
                elseif ($constr->nodeName == "lt" ||
                    $constr->nodeName == "lte" ||
                    $constr->nodeName == "gt" ||
                    $constr->nodeName == "gte"
                ) {
                    $metadataConstraint->setComparison(
                        $constr->nodeName,
                        $nodeTextContent
                    );
                }
            }
        }

        return $metadataConstraint;
    }
}
