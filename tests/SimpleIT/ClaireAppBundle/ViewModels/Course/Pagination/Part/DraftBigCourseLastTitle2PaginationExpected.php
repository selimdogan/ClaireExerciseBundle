<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub3;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftBigCourseLastTitle2PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTitleTwoStub3::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/1/102?status=draft';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;
}
