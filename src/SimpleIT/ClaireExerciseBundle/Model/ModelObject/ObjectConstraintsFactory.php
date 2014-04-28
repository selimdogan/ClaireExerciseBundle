<?php

namespace SimpleIT\ClaireExerciseBundle\Model\ModelObject;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ModelObject\ObjectId;

/**
 * Factory that manages the creation of ObjectConstraints
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ObjectConstraintsFactory
{

    /**
     * Creates an ObjectConstraint (for exercise model) from a DomNode
     *
     * @param \DOMNode $node
     * @param int      $type
     *
     * @return ObjectConstraints
     * @throws \Exception
     */
    public static function createFromDomNode(\DOMNode $node, $type = null)
    {
        $oc = new ObjectConstraints();

        // overwrite the type if any is found in the DomNode
        if ($type != null) {
            $oc->setType($type);
        } else {
            $param = $node->attributes
                ->getNamedItem("type");
            if (!is_null($param)) {
                $param = $param->nodeValue;
            }

            $oc->setType($param);
        }

        // object content
        $nodes = $node->childNodes;
        foreach ($nodes as $childNode) {
            // complex nodes (metadata)
            if ($childNode->nodeName == "metadata-constraint") {
                $oc->addMetadataConstraint(
                    MetadataConstraintFactory::createFromDomNode($childNode)
                );
            } // text nodes
            else {
                $nodeTextContent = $childNode->textContent;
                switch ($childNode->nodeName) {
                    case "excluded-resource" :
                        $excluded = new ObjectId();
                        $excluded->setId($nodeTextContent);
                        $oc->addExcluded($excluded);
                        break;
                }
            }
        }

        return $oc;
    }
}
