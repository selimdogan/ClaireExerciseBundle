<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PublishWaitingForPublicationCourseTest extends ChangeCourseStatusTest
{
    public function setUp()
    {
        $this->useCase = new PublishWaitingForPublicationCourse();
        parent::setUp();
    }
}
