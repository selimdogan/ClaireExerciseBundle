<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleOneStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TitleOneBisStub extends PartResource
{
    const ID = 11;

    const SUBTYPE = self::TITLE_1;

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array(New TitleTwoTerStub(), new TitleTwoQuatroStub());
    }

}
