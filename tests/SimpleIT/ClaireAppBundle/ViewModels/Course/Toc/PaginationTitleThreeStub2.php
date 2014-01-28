<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PaginationTitleThreeStub2 extends PartResource
{
    const ID = 1001;

    const TITLE = 'Title Three title 2';

    const SLUG = 'title-three-title-2';

    const SUBTYPE = self::TITLE_3;

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array(new TitleFourStub(), new TitleFourStub());
    }
}
