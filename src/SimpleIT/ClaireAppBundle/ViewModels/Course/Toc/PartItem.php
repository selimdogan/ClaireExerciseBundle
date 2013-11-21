<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class PartItem
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartItem extends TocItemDisplay
{
    const SUBTYPE = 'part';

    public $subtype = self::SUBTYPE;
}
