<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTocStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedBigCourseFirstTitle2PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTocStub1::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/course-1-title';

    const NEXT_TITLE = PaginationTitleTwoStub2::TITLE;

    const NEXT_URL = '/category-slug/cours/course-1-title/title-two-title-2';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
