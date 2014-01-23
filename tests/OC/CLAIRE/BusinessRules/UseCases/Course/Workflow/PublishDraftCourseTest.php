<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishDraftCourseTest extends ChangeCourseStatusTest
{
    public function setUp()
    {
        $this->useCase = new PublishDraftCourse();
        parent::setUp();
    }
}
