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
    protected $path = 'courses/{courseIdentifier}/metadatas/{metadataIdentifier}';

    /**
     * @type string
     */
    protected $resourceClass = '';

    /**
     * @return string
     */
    public function findDraft($courseId)
    {
        return parent::findResource(
            array('courseIdentifier' => $courseId, 'metadataIdentifier' => 'difficulty'),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
    }

    public function update($courseId, $difficulty)
    {
        return parent::updateResource(
            $difficulty,
            array('courseIdentifier' => $courseId, 'metadataIdentifier' => 'difficulty'),
            array(CourseResource::STATUS => CourseResource::STATUS_DRAFT)
        );
    }
}
