<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class Status
{
    const DRAFT = 'draft';

    const WAITING_FOR_PUBLICATION = 'waiting-for-publication';

    const PUBLISHED = 'published';
}
