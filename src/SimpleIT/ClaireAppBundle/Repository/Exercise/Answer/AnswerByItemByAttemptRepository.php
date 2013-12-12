<?php

namespace SimpleIT\ClaireAppBundle\Repository\Exercise\Answer;

use SimpleIT\ApiResourcesBundle\Exercise\AnswerResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class AnswerRepository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerByItemByAttemptRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'attempts/{attemptId}/items/{itemId}/answers/{answerId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Exercise\AnswerResource';

    /**
     * Insert an answer
     *
     * @param int            $attemptId
     * @param int            $itemId
     * @param AnswerResource $resource
     *
     * @return mixed
     */
    public function insert($attemptId, $itemId, AnswerResource $resource)
    {
        return $this->insertResource(
            $resource,
            array(
                'attemptId' => $attemptId,
                'itemId'    => $itemId
            )
        );
    }
}
