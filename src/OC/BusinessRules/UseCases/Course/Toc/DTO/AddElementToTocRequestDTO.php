<?php

namespace OC\BusinessRules\UseCases\Course\Toc\DTO;

use OC\BusinessRules\Requestors\Course\Toc\AddElementToTocRequest;

/**
 * Class AddElementToTocRequestDTO
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AddElementToTocRequestDTO implements AddElementToTocRequest
{
    /**
     * @var string
     */
    public $courseId;

    /**
     * @var string
     */
    public $parentId;

    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

}
