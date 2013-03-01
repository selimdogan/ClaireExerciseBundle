<?php

/**
 * This file is part of the Claire App Package
 *
 * PHP version 5
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */

namespace SimpleIT\ClaireAppBundle\Services\CourseAssociation;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\AssociatedCourseRepository;

/**
 * Description of AssociatedCourseService
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */
class AssociatedCourseService extends ClaireApi
    implements AssociatedCourseServiceInterface
{
    /** @var ClaireApi */
    private $claireApi;

    /** @var AssociatedCourseRepo */
    private $associatedCourseRepo;

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi The CLAIRE Api
     *
     * @return ClaireApi
     */
    public function setClaireApi(ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /**
     * Setter for $associatedCourseRepository
     *
     * @param AssociatedCourseRepository $associatedCourseRepo The Repository
     *
     * @return AssociationCourseRepository
     */
    public function setAssociatedCourseRepository(
        AssociatedCourseRepository $associatedCourseRepo
    ) {
        $this->associatedCourseRepo = $associatedCourseRepo;
    }

    /**
     * Get associated courses for course or part identifier
     *
     * @param string|integer $courseIdentifier The Course identifier
     *      <ul>
     *          <li>id : the course id (integer)</li>
     *          <li>slug : the course slug (string)</li>
     *      </ul>
     * @param string|integer $partIdentifier   The Part identifier
     *      <ul>
     *          <li>id : the part id (integer)</li>
     *          <li>slug : the part slug (string)</li>
     *      </ul>
     * @param integer        $max              Requested associated courses Qty
     *
     * @return array | null
     */
    public function getAssociatedCourse($courseIdentifier, $partIdentifier, $max)
    {
        if (is_null($partIdentifier)) {
            /* Retrieve associatedCourses for a course */
            $allAssociations = $this->associatedCourseRepo
                ->findAssociatedCourse($courseIdentifier);
        } else {
            /* Retrieve associatedCourses for a part */
            $partAssociations = $this->associatedCourseRepo
                ->findAssociatedCourse($courseIdentifier, $partIdentifier);
            if (count($partAssociations) > $max) {
                $allAssociations = $partAssociations;
            } else {
                /* Try to add Course's associatedCourses
                 * if part doesn't have enougth associations */
                $courseAssociations = $this->associatedCourseRepo
                    ->findAssociatedCourse($courseIdentifier);
                $allAssociations = array_merge(
                    $partAssociations,
                    $courseAssociations
                );
            }
        }

        /* Return associatedCourses maximum requested */
        $selectedCourses = null;
        if (count($allAssociations) > $max) {
            for ($i = 0; $i < $max; $i++) {
                $selectedCourses[] = $allAssociations[$i];
            }
        } else {
            $selectedCourses = $allAssociations;
        }

        return $selectedCourses;
    }
}
