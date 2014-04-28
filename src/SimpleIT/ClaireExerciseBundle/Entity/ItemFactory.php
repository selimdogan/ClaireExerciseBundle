<?php

namespace SimpleIT\ClaireExerciseBundle\Entity;

use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;

/**
 * Class to manage the creation of StoredExercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ItemFactory
{
    /**
     * Create a new Item object
     *
     * @param string $content
     * @param string $type
     *
     * @return Item
     */
    public static function create($content, $type)
    {
        $item = new Item();
        $item->setContent($content);
        $item->setType($type);

        return $item;
    }
}
