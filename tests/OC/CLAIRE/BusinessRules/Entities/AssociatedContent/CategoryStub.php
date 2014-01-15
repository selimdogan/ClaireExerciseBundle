<?php

namespace OC\CLAIRE\BusinessRules\Entities\AssociatedContent;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Category\Category;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CategoryStub extends Category
{
    const ID = 1;

    const NAME = 'Informatique';

    const SLUG = 'informatique-slug';

    protected $id = self::ID;

    protected $name = self::NAME;

    protected $slug = self::SLUG;
}
