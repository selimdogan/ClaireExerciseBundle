<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleOneStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PaginationTitleOneStub1 extends PartResource
{
    const ID = 10;

    const SUBTYPE = self::TITLE_1;

    const TITLE = 'Title One title 1';

    const SLUG = 'title-one-title-1';

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    public function __construct()
    {
        $this->children = array(new PaginationTitleTwoStub1(), new PaginationTitleTwoStub2(), new PaginationTitleTwoStub3());
    }

}
