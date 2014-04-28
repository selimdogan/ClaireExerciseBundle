<?php


namespace SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\Validable;

/**
 * Class CommonKnowledge
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "object_type", map = {
 *    "formula": "SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula"
 * })
 */
abstract class CommonKnowledge implements Validable
{
    /**
     * @const FORMULA = "formula"
     */
    const FORMULA = "formula";

    /**
     * Checks if a type of knowledge is valid
     *
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType($type)
    {
        if (
            $type === self::FORMULA
        ) {
            return true;
        }

        return false;
    }
}
