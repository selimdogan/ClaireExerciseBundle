<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Part;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination\Pagination;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub1;
use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\PaginationTitleTwoStub3;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftBigCourseSecondTitle2PaginationExpected extends Pagination
{
    const PREVIOUS_TITLE = PaginationTitleTwoStub1::TITLE;

    const PREVIOUS_URL = '/category-slug/cours/1/100?status=draft';

    const NEXT_TITLE = PaginationTitleTwoStub3::TITLE;

    const NEXT_URL = '/category-slug/cours/1/102?status=draft';

    public $previousTitle = self::PREVIOUS_TITLE;

    public $previousUrl = self::PREVIOUS_URL;

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;

}
