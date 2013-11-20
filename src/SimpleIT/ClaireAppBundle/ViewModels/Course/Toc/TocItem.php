<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class TocItem
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
abstract class TocItem
{
    /**
     * @var string
     */
    public $subtype;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $children = array();

}
