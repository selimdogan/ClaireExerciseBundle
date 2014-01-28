<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class TocItemDisplay
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
abstract class TocItemDisplay extends TocItem
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $image;
}
