<?php
namespace SimpleIT\ClaireAppBundle\Services\CourseAssociation;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\AssociatedCourseRepository;

/**
 * Description of AssociatedCourseService
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */
class AssociatedCourseService extends ClaireApi implements AssociatedCourseServiceInterface
{
    /** @var ClaireApi */
    private $claireApi;

    /** @var AssociatedCourseRepository */
    private $associatedCourseRepository;

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi(ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /**
     * Setter for $associatedCourseRepository
     *
     * @param AssociatedCourseRepository $associatedCourseRepository
     */
    public function setAssociatedCourseRepository(AssociatedCourseRepository $associatedCourseRepository)
    {
        $this->associatedCourseRepository = $associatedCourseRepository;
    }

    /**
     * Get associated courses for course or part identifier
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
     * @return array
     */
    public function getAssociatedCourse($CourseIdentifier, $partIdentifier, $max)
    {
        $associatedCourses = $this->associatedCourseRepository->findAssociatedCourse($CourseIdentifier, $partIdentifier);
        $randomKeys = array_rand($associatedCourses, $max);
        foreach ($randomKeys as $key) {
            $selectedCourses[] = $associatedCourses[$key];
        }

        return $selectedCourses;
    }
}
