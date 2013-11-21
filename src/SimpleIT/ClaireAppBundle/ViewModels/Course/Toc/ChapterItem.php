<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class ChapterItem
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ChapterItem extends TocItemDisplay
{
    const SUBTYPE = 'chapter';

    public $subtype = self::SUBTYPE;
}
