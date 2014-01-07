<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Toc;

use OC\CLAIRE\BusinessRules\Requestors\Course\Toc\AddElementToTocRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Toc\AddElementToTocResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Toc\DTO\AddElementToTocRequestDTO;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * Class AddElementToTocTest
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AddElementToTocTest extends \PHPUnit_Framework_TestCase
{
    const NEW_ELEMENT_ID_EXPECTED = 999;

    const EXPECTED_ELEMENTS_COUNT = 32;

    private $elementCount = 0;

    /**
     * @var AddElementToTocResponse
     */
    private $response;

    /**
     * @var AddElementToTocRequest
     */
    private $request;

    /**
     * @var AddElementToToc
     */
    private $useCase;

    /**
     * @test
     */
    public function countShouldBeCorrectAfterAddingAPart()
    {
        $this->makeAddPartUseCase();
        $this->executeUseCase();

        $this->elementCount = 0;
        $this->countTocElements($this->response->getToc());
        $this->assertEquals(self::EXPECTED_ELEMENTS_COUNT, $this->elementCount);
    }

    private function makeAddPartUseCase()
    {
        $this->buildAddPartRequest();
        $this->useCase = new AddElementToToc(new TocByCourseRepositoryStub());
    }

    private function buildAddPartRequest()
    {
        $this->request = new AddElementToTocRequestDTO();
        $this->request->setParentId(1);
        $this->request->setCourseId(1);
    }

    private function executeUseCase()
    {
        $this->response = $this->useCase->execute($this->request);
    }

    private function countTocElements(PartResource $parent)
    {
        $this->elementCount++;
        foreach ($parent->getChildren() as $child) {
            $this->countTocElements($child);
        }
    }

    /**
     * @test
     */
    public function countShouldBeCorrectAfterAddingAPartOnAnEmptyToc()
    {
        $this->makeAddPartUseCaseOnEmptyToc();
        $this->executeUseCase();

        $this->elementCount = 0;
        $this->countTocElements($this->response->getToc());
        $this->assertEquals(2, $this->elementCount);
    }

    private function makeAddPartUseCaseOnEmptyToc()
    {
        $this->buildAddPartRequest();
        $this->useCase = new AddElementToToc(new TocByCourseWithEmptyTocRepositoryStub());
    }

    /**
     * @test
     */
    public function countShouldBeCorrectAfterAddingAChapterOnAnEmptyPart()
    {
        $this->makeAddChapterUseCaseOnEmptyToc();
        $this->executeUseCase();

        $this->elementCount = 0;
        $this->countTocElements($this->response->getToc());
        $this->assertEquals(3, $this->elementCount);
    }

    private function makeAddChapterUseCaseOnEmptyToc()
    {
        $this->buildAddChapterRequest();
        $this->useCase = new AddElementToToc(new TocByCourseWithEmptyPartRepositoryStub());
    }

    /**
     * @test
     */
    public function countShouldBeCorrectAfterAddingAChapter()
    {
        $this->makeAddChapterUseCase();
        $this->executeUseCase();

        $this->elementCount = 0;
        $this->countTocElements($this->response->getToc());
        $this->assertEquals(self::EXPECTED_ELEMENTS_COUNT, $this->elementCount);
    }

    private function makeAddChapterUseCase()
    {
        $this->buildAddChapterRequest();
        $this->useCase = new AddElementToToc(new TocByCourseRepositoryStub());
    }

    private function buildAddChapterRequest()
    {
        $this->request = new AddElementToTocRequestDTO();
        $this->request->setParentId(10);
        $this->request->setCourseId(1);
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function countShouldBeCorrectAfterAddingAnotherThing()
    {
        $this->buildAddSubChapterRequest();
        $this->useCase = new AddElementToToc(new TocByCourseRepositoryStub());
        $this->response = $this->useCase->execute($this->request);
    }

    private function buildAddSubChapterRequest()
    {
        $this->request = new AddElementToTocRequestDTO();
        $this->request->setParentId(100);
        $this->request->setCourseId(1);
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function exceptionShouldBeThrownAfterAddingFromAParentThatDoesNotExist()
    {
        $this->request = new AddElementToTocRequestDTO();
        $this->request->setParentId(-1);
        $this->request->setCourseId(1);
        $this->useCase = new AddElementToToc(new TocByCourseRepositoryStub());
        $this->response = $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function addAPartShouldReturnTheNewElement()
    {
        $this->makeAddPartUseCase();
        $this->executeUseCase();
        $this->assertEquals(
            self::NEW_ELEMENT_ID_EXPECTED,
            $this->response->getNewElement()->getId()
        );
        $this->assertEquals(PartResource::TITLE_1, $this->response->getNewElement()->getSubtype());
    }

    /**
     * @test
     */
    public function addAChapterShouldReturnTheNewElement()
    {
        $this->makeAddChapterUseCase();
        $this->executeUseCase();
        $this->assertEquals(
            self::NEW_ELEMENT_ID_EXPECTED,
            $this->response->getNewElement()->getId()
        );
        $this->assertEquals(PartResource::TITLE_2, $this->response->getNewElement()->getSubtype());
    }

    public function addAPartShouldProvideANewPartAtTheEnd()
    {
        $this->makeAddPartUseCase();
    }

}
