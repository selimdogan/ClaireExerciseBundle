<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Toc\DTO;

use OC\CLAIRE\BusinessRules\Requestors\Course\Toc\AddElementToTocRequest;

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

    function __construct($courseId, $parentId)
    {
        $this->courseId = $courseId;
        $this->parentId = $parentId;
    }

    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

}
