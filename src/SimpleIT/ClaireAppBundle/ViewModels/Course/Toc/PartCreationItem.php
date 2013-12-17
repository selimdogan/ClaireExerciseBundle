<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class PartCreationItem
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartCreationItem extends TocItemCreation
{
    const SUBTYPE = 'part-creation';

    public $subtype = self::SUBTYPE;
}
