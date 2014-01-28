<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Part;

use OC\CLAIRE\BusinessRules\Gateways\Course\Part\PartGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Responders\Course\Part\GetPartResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Part\DTO\GetPartResponseDTO;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetPart implements UseCase
{
    /**
     * @var PartGateway
     */
    protected $partGateway;

    /**
     * @var PartResource::
     */
    protected $part;

    /**
     * @var GetPartResponse
     */
    protected $response;

    public function setPartGateway(PartGateway $partGateway)
    {
        $this->partGateway = $partGateway;
    }

    public function buildResponse()
    {
        $this->response = new GetPartResponseDTO();
        $this->response->createdAt = $this->part->getCreatedAt();
        $this->response->description = $this->part->getDescription();
        $this->response->difficulty = $this->part->getDifficulty();
        $this->response->duration = $this->part->getDuration();
        $this->response->id = $this->part->getId();
        $this->response->slug = $this->part->getSlug();
        $this->response->subtype = $this->part->getSubtype();
        $this->response->title = $this->part->getTitle();
        $this->response->updatedAt = $this->part->getUpdatedAt();
    }
}
