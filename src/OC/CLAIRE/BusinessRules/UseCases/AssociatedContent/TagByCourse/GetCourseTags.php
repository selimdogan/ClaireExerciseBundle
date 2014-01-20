<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse;

use OC\CLAIRE\BusinessRules\Entities\AssociatedContent\Tag\Tag;
use OC\CLAIRE\BusinessRules\Gateways\AssociatedContent\Tag\TagByCourseGateway;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO\GetCourseTagsResponseDTO;
use OC\CLAIRE\BusinessRules\UseCases\AssociatedContent\TagByCourse\DTO\TagResponseDTO;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class GetCourseTags implements UseCase
{
    /**
     * @var TagByCourseGateway
     */
    protected $tagByCourseGateway;

    /**
     * @var Tag[]
     */
    protected $tags;

    public function setTagByCourseGateway(TagByCourseGateway $tagByCourseGateway)
    {
        $this->tagByCourseGateway = $tagByCourseGateway;
    }

    protected function buildResponse()
    {
        $response = new GetCourseTagsResponseDTO();
        foreach ($this->tags as $tag) {
            $response->tags[] = $this->buildTagResponse($tag);
        }

        return $response;
    }

    /**
     * @return TagResponseDTO
     */
    private function buildTagResponse($tag)
    {
        $tagResponse = new TagResponseDTO();
        $tagResponse->id = $tag->getId();
        $tagResponse->image = $tag->getImage();
        $tagResponse->name = $tag->getName();
        $tagResponse->slug = $tag->getSlug();

        return $tagResponse;
    }
}
