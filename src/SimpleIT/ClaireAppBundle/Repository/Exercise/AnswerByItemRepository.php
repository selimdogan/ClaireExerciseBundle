<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise;

use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class AnswerRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerByItemRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'items/{itemId}/answers/{answerId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\AnswerResource';
}
