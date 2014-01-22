<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Course;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedBigCoursePaginationExpected extends Pagination
{
    const NEXT_TITLE = PaginationTitleTwoStub1::TITLE;

    const NEXT_URL = '/category-slug/cours/course-1-title/title-two-title-1';

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
