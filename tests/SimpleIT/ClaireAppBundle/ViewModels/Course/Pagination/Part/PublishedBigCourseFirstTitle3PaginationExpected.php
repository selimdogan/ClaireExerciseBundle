<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleThreeStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedBigCourseFirstTitle3PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTitleTwoStub1::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/course-1-title/title-two-title-1';

    const NEXT_TITLE = PaginationTitleThreeStub2::TITLE;

    const NEXT_URL = '/category-slug/cours/course-1-title/title-three-title-2';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
