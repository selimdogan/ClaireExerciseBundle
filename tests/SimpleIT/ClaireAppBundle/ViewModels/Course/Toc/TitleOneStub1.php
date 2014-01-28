<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleOneStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TitleOneStub1 extends PartResource
{
    const ID = 10;

    const SUBTYPE = self::TITLE_1;

    const TITLE = 'TitleOne title 1';

    const SLUG = 'titleone-title-1';

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    public function __construct()
    {
        $this->children = array(new TitleTwoStub1(), new TitleTwoStub2(), new TitleTwoStub3());
    }

}
