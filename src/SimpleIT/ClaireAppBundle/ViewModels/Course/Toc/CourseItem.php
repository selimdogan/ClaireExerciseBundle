<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class CourseItem
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseItem extends TocItemDisplay
{
    const SUBTYPE = 'course';

    public $subtype = self::SUBTYPE;
}
