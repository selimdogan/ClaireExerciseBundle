<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleOneStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TitleOneStub3 extends PartResource
{
    const ID = 12;

    const SUBTYPE = self::TITLE_1;

    const TITLE = 'TitleOne title 3';

    const SLUG = 'titleone-title-3';

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    public function __construct()
    {
        $this->children = array(New TitleTwoStub(), new TitleTwoStub());
    }

}
