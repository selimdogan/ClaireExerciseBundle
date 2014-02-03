<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class TocStub1
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class EmptyPartTocStub extends PartResource
{
    const ID = 1;

    const SUBTYPE = 'course';

    protected $id = self::ID;

    protected $subtype = self::SUBTYPE;

    protected $children = array();

    public function __construct()
    {
        $this->children = array(new TitleOneWithoutChildStub());
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

}
