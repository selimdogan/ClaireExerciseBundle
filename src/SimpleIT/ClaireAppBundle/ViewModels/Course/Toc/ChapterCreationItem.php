<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class ChapterCreationItem
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ChapterCreationItem extends TocItemCreation
{
    const SUBTYPE = 'chapter-creation';

    public $subtype = self::SUBTYPE;
}
