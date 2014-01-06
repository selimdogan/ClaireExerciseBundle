<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGateway;
use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDifficultyRepository extends AppRepository implements CourseDifficultyGateway
{
    /**
     * @type string
     */
    protected $path = 'courses/{courseIdentifier}';

    /**
     * @type string
     */
    protected $resourceClass = '';

    /**
     * @return string
     */
    public function findDraft($courseId)
    {
        /** @var CourseResource $course */
        $course = parent::findResource(
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
        return $course->getDifficulty();
    }

    public function update($courseId, $difficulty)
    {
        $course = new CourseResource();
        $course->setDifficulty($difficulty);
        return parent::updateResource(
            $course,
            array('courseIdentifier' => $courseId),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
    }
}
