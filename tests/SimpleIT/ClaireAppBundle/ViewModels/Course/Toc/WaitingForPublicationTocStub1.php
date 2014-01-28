<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class WaitingForPublicationTocStub1 extends TocStub1
{
    const STATUS = Status::WAITING_FOR_PUBLICATION;

    protected $status = self::STATUS;
}
