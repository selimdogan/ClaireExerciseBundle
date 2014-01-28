<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleOneStub3;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftMediumCourseSecondTitle1PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTitleOneStub1::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/1/10?status=draft';

    const NEXT_TITLE = PaginationTitleOneStub3::TITLE;

    const NEXT_URL = '/category-slug/cours/1/12?status=draft';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;

}
