<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishedTocStub1 extends TocStub1
{
    const STATUS = Status::PUBLISHED;

    protected $status = self::STATUS;

}