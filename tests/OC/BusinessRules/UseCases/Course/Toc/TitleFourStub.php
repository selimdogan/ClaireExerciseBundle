<?php

namespace OC\BusinessRules\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TitleFourStub
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TitleFourStub extends PartResource
{
    const ID = 10000;

    const SUBTYPE = self::TITLE_4;

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    public function __construct()
    {
        $this->children = array();
    }
}
