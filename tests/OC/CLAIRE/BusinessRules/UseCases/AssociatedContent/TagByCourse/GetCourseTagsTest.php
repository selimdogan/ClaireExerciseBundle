<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\TagStub1;
use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\TagStub2;
use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag\CourseNotFoundTagByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\AssociatedContent\TagByCourse\GetCourseTagsRequest;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse\GetCourseTagsResponse;
use OC\CLAIRE\BusinessRules\Responders\AssociatedContent\Tag\TagByCourse\TagResponse;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseTagsTest extends \PHPUnit_Framework_TestCase
{
    const COURSE_ID = 1;

    /**
     * @var GetCourseTags
     */
    protected $useCase;

    /**
     * @var GetCourseTagsRequest
     */
    protected $request;

    /**
     * @var GetCourseTagsResponse
     */
    protected $response;

    /**
     * @var TagResponse
     */
    protected $tagResponse;

    /**
     * @var int
     */
    protected $tagIndex = 0;

    /**
     * @var int[]
     */
    protected $tagsId = array(TagStub1::ID, TagStub2::ID);

    /**
     * @var string[]
     */
    protected $tagsImage = array(TagStub1::IMAGE, TagStub2::IMAGE);

    /**
     * @var string[]
     */
    protected $tagsName = array(TagStub1::NAME, TagStub2::NAME);

    /**
     * @var string[]
     */
    protected $tagsSlug = array(TagStub1::SLUG, TagStub2::SLUG);

    /**
     * @test
     * @expectedException \OC\CLAIRE\BusinessRules\Exceptions\Course\Course\CourseNotFoundException
     */
    public function NonExistingCourse_ThrowException()
    {
        $this->useCase->setTagByCourseGateway(new CourseNotFoundTagByCourseGateway());
        $this->executeUseCase();
    }

    protected function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    protected function assertResponse()
    {
        foreach ($this->response->getTags() as $this->tagResponse) {
            $this->assertTagResponse();
            $this->tagIndex++;
        }
    }

    protected function assertTagResponse()
    {
        $this->assertEquals($this->tagsId[$this->tagIndex], $this->tagResponse->getId());
        $this->assertEquals($this->tagsImage[$this->tagIndex], $this->tagResponse->getImage());
        $this->assertEquals($this->tagsName[$this->tagIndex], $this->tagResponse->getName());
        $this->assertEquals($this->tagsSlug[$this->tagIndex], $this->tagResponse->getSlug());
    }
}
