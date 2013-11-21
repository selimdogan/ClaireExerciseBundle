<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\UseCases\Course\Toc\DTO\AddElementToTocRequestDTO;
use SimpleIT\ClaireAppBundle\UseCases\Course\Toc\DTO\AddElementToTocResponseDTO;

/**
 * Class AddElementToTocTest
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AddElementToTocTest extends \PHPUnit_Framework_TestCase
{
    const EXPECTED_ELEMENTS_COUNT = 32;

    private $elementCount = 1;

    private $response;

    private $request;

    private $useCase;

    /**
     * @test
     */
    public function countShouldBeCorrectAfterAddingAPart()
    {
        $this->makeAddPartUseCase();
        /** @var AddElementToTocResponseDTO $response */
        $this->response = $this->useCase->execute($this->request);

        $this->countTocElements($this->response->toc);
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

    private function countTocElements(PartResource $parent)
    {
        foreach ($parent->getChildren() as $child) {
            $this->elementCount++;
            $this->countTocElements($child);
        }
    }

    /**
     * @test
     */
    public function countShouldBeCorrectAfterAddingAChapter()
    {
        $this->buildAppChapterRequest();
        $this->useCase = new AddElementToToc(new TocByCourseRepositoryStub());
        /** @var AddElementToTocResponseDTO $response */
        $this->response = $this->useCase->execute($this->request);

        $this->countTocElements($this->response->toc);
        $this->assertEquals(self::EXPECTED_ELEMENTS_COUNT, $this->elementCount);
    }

    private function buildAppChapterRequest()
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

    public function addAPartShouldProvideANewPartAtTheEnd()
    {
        $this->makeAddPartUseCase();
    }

}
