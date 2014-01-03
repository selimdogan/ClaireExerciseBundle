<?php

namespace SimpleIT\ClaireAppBundle\Repository\Course;

use OC\CLAIRE\BusinessRules\Gateways\Course\Difficulty\CourseDifficultyGateway;
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
    public function find($courseId)
    {
        return parent::findResource(
            array('courseIdentifier' => $courseId, 'metadataIdentifier' => 'difficulty')
        );
    }

    public function update($courseId, $difficulty)
    {
        return null;
    }
}
