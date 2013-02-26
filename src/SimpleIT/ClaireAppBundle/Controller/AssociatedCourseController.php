<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use SimpleIT\ClaireAppBundle\Model\Course\Part;
use SimpleIT\ClaireAppBundle\Model\Metadata;
use SimpleIT\ClaireAppBundle\Model\CourseFactory;
use SimpleIT\ClaireAppBundle\Services\CourseService;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\AppBundle\Services\ApiService;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * Description of AssociatedCourseController
 *
 * @author Vincent DELVINQUIERE <vincent.delvinquiere@simple-it.fr>
 */
class AssociatedCourseController extends BaseController
{
    /**
     * @var Service The course service
     */
    private $associatedCourseService;

    /**
     * Constructor
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->associatedCourseService = $this->container->get('simpleit.claire.associated_course');
    }

    /**
     * Get associated courses for course and part identifier
     *
     * @param string|integer $courseIdentifier
     *      <ul>
     *          <li>id : the course id (integer)</li>
     *          <li>slug : the course slug (string)</li>
     *      </ul>
     * @param string|integer $partIdentifier
     *      <ul>
     *          <li>id : the part id (integer)</li>
     *          <li>slug : the part slug (string)</li>
     *      </ul>
     * @param integer $max Nbr of requested associated courses
     *
     * @return array
     */
    public function getAssociatedCoursesAction($courseIdentifier, $partIdentifier, $max)
    {
        return $this->associatedCourseService->getAssociatedCourse($courseIdentifier, $partIdentifier, $max);
    }
}
