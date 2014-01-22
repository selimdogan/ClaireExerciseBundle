<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Course;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftMediumCoursePaginationExpected extends Pagination
{
    const NEXT_TITLE = PaginationTitleOneStub1::TITLE;

    const NEXT_URL = '/category-slug/cours/1/10?status=draft';

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
