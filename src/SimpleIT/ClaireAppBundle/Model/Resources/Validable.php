<?php


namespace SimpleIT\ApiResourcesBundle\Exercise;

/**
 * Interface Validable
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface Validable
{
    /**
     * Validate the structure and content of the object
     *
     * @throws \Exception If the validation fails.
     */
    public function validate($param = null);
}
