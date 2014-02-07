<?php

namespace OC\CLAIRE\BusinessRules\UseCases\Course\Course;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Status;
use OC\CLAIRE\BusinessRules\Requestors\Course\Course\GetCourseStatusesRequest;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseRequest;
use OC\CLAIRE\BusinessRules\Responders\Course\Course\GetCourseStatusesResponse;
use OC\CLAIRE\BusinessRules\Responders\UseCaseResponse;
use OC\CLAIRE\BusinessRules\UseCases\Course\Course\DTO\GetCourseStatusesResponseBuilderImpl;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class GetCourseStatuses extends GetCourse
{

    /**
     * @var CourseResource[]
     */
    private $courses;

    /**
     * @var GetCourseStatusesResponse
     */
    private $response;

    /**
     * @return UseCaseResponse
     */
    public function execute(UseCaseRequest $useCaseRequest)
    {
        /** @var GetCourseStatusesRequest $useCaseRequest */
        $this->courses = $this->courseGateway->findAllStatus(
            $useCaseRequest->getCourseIdentifier()
        );

        $this->buildResponse();

        return $this->response;
    }

    private function buildResponse()
    {
        $builder = GetCourseStatusesResponseBuilderImpl::create();

        foreach ($this->courses as $course) {
            $courseResponse = $this->buildCourseResponse($course);
            switch ($course->getStatus()) {
                case Status::DRAFT:
                    $builder->withDraft($courseResponse);
                    break;
                case Status::WAITING_FOR_PUBLICATION:
                    $builder->withWaitingForPublication($courseResponse);
                    break;
                case Status::PUBLISHED:
                    $builder->withPublished($courseResponse);
                    break;
                // @codeCoverageIgnoreStart
                default:
                    break;
                // @codeCoverageIgnoreEnd
            }
        }
        $this->response = $builder->build();
    }
}
