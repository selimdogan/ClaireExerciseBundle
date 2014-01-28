<?php
namespace OC\CLAIRE\BusinessRules\UseCases\Course\Workflow;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ChangeCourseToWaitingForPublicationTest extends ChangeCourseStatusTest
{

    protected function setUp()
    {
        $this->useCase = new ChangeCourseToWaitingForPublication();
        parent::setUp();
    }

}
