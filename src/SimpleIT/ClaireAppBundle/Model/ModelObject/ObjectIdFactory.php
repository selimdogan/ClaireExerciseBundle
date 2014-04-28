<?php

namespace SimpleIT\ExerciseBundle\Model\ModelObject;

use SimpleIT\ApiResourcesBundle\Exercise\ModelObject\ObjectId;

/**
 * Factory that manages the creation of ObjectId
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ObjectIdFactory
{

    /**
     * Creates an ObjectId (for exercise model) from a DomNode
     *
     * @param \DOMNode $node
     *
     * @return ObjectId
     */
    public static function createFromDomNode(\DOMNode $node)
    {
        $oId = new ObjectId();

        // copy id
        $oId->setId($node->textContent);

        // return the object
        return $oId;
    }

    /**
     * Creates an ObjectId (for exercise model) from an id as it can be retrieved
     * from metadata value
     *
     * @param int $id
     *
     * @return ObjectId
     */
    public static function createFromResourceId($id)
    {
        $oId = new ObjectId();
        $oId->setId($id);

        return $oId;
    }
}
