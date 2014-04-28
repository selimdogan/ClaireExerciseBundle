<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common;

/**
 * Interface Markable
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
interface Markable
{
    /**
     * Check if the Markable has a mark
     *
     * @return boolean
     */
    public function isMarked();

    /**
     * Get mark
     *
     * @return float
     */
    public function getMark();

    /**
     * Set mark
     *
     * @param float $mark
     */
    public function setMark($mark);
}
