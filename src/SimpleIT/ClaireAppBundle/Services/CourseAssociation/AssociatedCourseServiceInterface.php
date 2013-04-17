<?php
namespace SimpleIT\ClaireAppBundle\Services\CourseAssociation;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\AssociatedCourseRepository;

/**
 * Description of AssociatedCourseServiceInterface
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */
interface AssociatedCourseServiceInterface
{
    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi(ClaireApi $claireApi);

    /**
     * Setter for $associatedCourseRepository
     *
     * @param AssociatedCourseRepository $associatedCourseRepository
     */
    public function setAssociatedCourseRepository(AssociatedCourseRepository $associatedCourseRepository);

    /**
     * Returns the associated courses for the course or part given
     *
     * @param string|integer $courseIdentifier
     *      <ul>
     *          <li>id : the course id (integer)</li>
     *          <li>slug : the course slug (string)</li>
     *      </ul>
     * @param string|integer $partIdentifier optional
     *      <ul>
     *          <li>id : the part id (integer)</li>
     *          <li>slug : the part slug (string)</li>
     *      </ul>
     * @param integer $max Nbr of requested associated courses
     *
     * @return type
     */
    public function getAssociatedCourse($CourseIdentifier, $partIdentifier, $max);
}