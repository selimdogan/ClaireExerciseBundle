<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Pagination;

use SimpleIT\ClaireAppBundle\ViewModels\Course\Toc\TitleOneStub;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DraftMediumCoursePaginationStub extends Pagination
{
    const NEXT_TITLE = TitleOneStub::TITLE;

    const NEXT_URL = '/category-slug/cours/1/10?status=draft';

    public $nextTitle = self::NEXT_TITLE;

    public $nextUrl = self::NEXT_URL;
}
