<?php

namespace SimpleIT\ClaireAppBundle\UseCases\Course\Toc;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Entity\Course\Part\PartFactoryImpl;
use SimpleIT\ClaireAppBundle\Gateways\Course\Toc\TocByCourseGateway;
use SimpleIT\ClaireAppBundle\Requestors\UseCase;
use SimpleIT\ClaireAppBundle\Requestors\UseCaseRequest;
use SimpleIT\ClaireAppBundle\UseCases\Course\Toc\DTO\AddElementToTocRequestDTO;
use SimpleIT\ClaireAppBundle\UseCases\Course\Toc\DTO\AddElementToTocResponseDTO;

/**
 * Class AddElementToToc
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class AddElementToToc implements UseCase
{
    private static $correspondingChildSubtype = array(
        PartResource::COURSE  => PartResource::TITLE_1,
        PartResource::TITLE_1 => PartResource::TITLE_2
    );

    /**
     * @var AddElementToTocRequestDTO
     */
    private $request;

    /**
     * @var AddElementToTocResponseDTO
     */
    private $response;

    /**
     * @var TocByCourseGateway
     */
    private $tocByCourseGateway;

    /**
     * @var int
     */
    private $parentId;

    /**
     * @var PartResource
     */
    private $toc;

    /**
     * @var PartResource
     */
    private $parent;

    /**
     * @var PartResource
     */
    private $child;

    /**
     * @var PartResource
     */
    private $createdChild;

    /**
     * @var bool
     */
    private $find = false;

    public function __construct(TocByCourseGateway $tocByCourseGateway)
    {
        $this->tocByCourseGateway = $tocByCourseGateway;
    }

    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var AddElementToTocRequestDTO $request */
        $this->request = $useCaseRequest;
        $this->parentId = $this->request->parentId;

        $this->toc = $this->tocByCourseGateway->findByStatus(
            $this->request->courseId,
            CourseResource::STATUS_DRAFT
        );
        $this->parent = $this->toc;
        $this->addChild();

        $this->buildResponse();

        return $this->response;
    }

    private function addChild()
    {
        /** @var PartResource $child */
        foreach ($this->parent->getChildren() as $this->child) {
            if ($this->isAlreadyAdded()) {
                if ($this->parent->getId() == $this->parentId) {
                    $this->addChildToToc();
                } else {
                    $this->parent = $this->child;
                    self::addChild();
                }
            }
        }
    }

    /**
     * @return bool
     */
    private function isAlreadyAdded()
    {
        return !$this->find;
    }

    private function addChildToToc()
    {
        $this->buildChild();
        $this->parent->addChild($this->createdChild);
        $this->find = true;
    }

    private function buildChild()
    {
        $partFactory = new PartFactoryImpl();
        if (!isset(self::$correspondingChildSubtype[$this->parent->getSubtype()])) {
            throw new \DomainException();
        }
        $this->createdChild = $partFactory->make(
            self::$correspondingChildSubtype[$this->parent->getSubtype()]
        );
        $this->createdChild->setTitle('Sans titre');
    }

    private function buildResponse()
    {
        $this->response = new AddElementToTocResponseDTO();
        $this->response->toc = $this->toc;
    }
}
