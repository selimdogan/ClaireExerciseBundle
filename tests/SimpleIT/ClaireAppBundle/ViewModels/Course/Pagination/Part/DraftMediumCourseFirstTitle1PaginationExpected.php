<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub2;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTocStub1;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftMediumCourseFirstTitle1PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTocStub1::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/1?status=draft';

    const NEXT_TITLE = PaginationTitleOneStub2::TITLE;

    const NEXT_URL = '/category-slug/cours/1/11?status=draft';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
