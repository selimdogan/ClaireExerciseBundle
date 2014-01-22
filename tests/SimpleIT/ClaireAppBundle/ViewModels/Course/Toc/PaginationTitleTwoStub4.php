<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleTwoStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PaginationTitleTwoStub4 extends PartResource
{
    const ID = 103;

    const TITLE = 'TitleTwo title 4';

    const SLUG = 'titletwo-title-4';

    const SUBTYPE = self::TITLE_2;

    protected $id = self::ID;

    protected $title = self::TITLE;

    protected $slug = self::SLUG;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array(new TitleTreeStub(), new TitleTreeStub());
    }
}
