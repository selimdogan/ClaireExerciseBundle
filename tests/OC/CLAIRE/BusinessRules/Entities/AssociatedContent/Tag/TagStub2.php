<?php

namespace OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TagStub2 extends Tag
{
    const ID = 2;

    const IMAGE = 'http://example.com/tag2.png';

    const NAME = 'Tag 2';

    const SLUG = 'tag-2';

    /**
     * @var int
     */
    protected $id = self::ID;

    /**
     * @var string
     */
    protected $slug = self::SLUG;

    /**
     * @var string
     */
    protected $name = self::NAME;

    /**
     * @var string
     */
    protected $image = self::IMAGE;

}
