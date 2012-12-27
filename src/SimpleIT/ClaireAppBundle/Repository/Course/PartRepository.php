<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;
use SimpleIT\ClaireAppBundle\Model\CategoryFactory;

use Symfony\Component\HttpKernel\Exception\HttpException;

use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;

use SimpleIT\ClaireAppBundle\Model\Course\Part;

use SimpleIT\ClaireAppBundle\Model\PartFactory;

use SimpleIT\ClaireAppBundle\Model\TocFactory;

use SimpleIT\ClaireAppBundle\Model\TagFactory;

use SimpleIT\ClaireAppBundle\Model\MetadataFactory;

use SimpleIT\AppBundle\Model\ApiRequestOptions;

use SimpleIT\ClaireAppBundle\Model\Course\Course;

use SimpleIT\ClaireAppBundle\Model\CourseFactory;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;

use SimpleIT\AppBundle\Model\ApiRequest;

use SimpleIT\AppBundle\Services\ApiRouteService;

/**
 * Class PartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartRepository extends ApiRouteService
{
    /** @var ClaireApi The Claire Api */
    private $claireApi;

    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses/';

    /** The base url for parts = '/parts/' */
    const URL_PART = '/part/';

    /** The url for metadatas  = '/metadatas' */
    const URL_PART_METADATAS = '/metadatas/';

    /** The url for metadatas  = '/tags' */
    const URL_PART_TAGS = '/tags/';

    /** The url for introduction = '/introduction' */
    const URL_PART_INTRODUCTION = '/introduction';

    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi(ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /* **************************** *
     *                              *
     * ********** METHODS ********* *
     *                              *
     * **************************** */

    /**
     * Returns a part
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return Part The part
     */
    public function find($courseIdentifier, $partIdentifier)
    {
        $partRequest = self::findRequest($courseIdentifier, $partIdentifier);

        $partResult = $this->claireApi->getResult($partRequest);
        $part = PartFactory::create($partResult->getContent());

        return $part;
    }

    /**
     * Returns a part
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param mixed  $partIdentifier   The part id | slug
     * @param string $format           The requested format
     *
     * @return Part The part
     */
    public function findContent($courseIdentifier, $partIdentifier, $format = null)
    {
        $partRequest = self::findRequest($courseIdentifier, $partIdentifier, $format);

        return $this->claireApi->getResult($partRequest)->getContent();
    }

    /**
     * <p>
     *     Returns the part complementaries
     *     <ul>
     *         <li>metadatas</li>
     *         <li>tags</li>
     *         <li>introduction</li>
     *     </ul>
     * </p>
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return array Part complementaries
     */
    public function findPartComplementaries($courseIdentifier, $partIdentifier)
    {
        $requests['metadatas'] = $this
            ->findPartMetadatasRequest($courseIdentifier, $partIdentifier);
        $requests['tags'] = self::findPartTagsRequest($courseIdentifier, $partIdentifier);
        $requests['introduction'] = $this
            ->findPartIntroductionRequest($courseIdentifier, $partIdentifier);

        $results = $this->claireApi->getResults($requests);

        if (!is_null($results['metadatas']->getContent())) {
            $metadatas = MetadataFactory::createCourseMetadataCollection(
                $results['metadatas']->getContent());
            $partComplementaries['metadatas'] = $metadatas;

        }
        if (!is_null($results['tags']->getContent())) {
            $tags = TagFactory::createCollection($results['tags']->getContent());
            $partComplementaries['tags'] = $tags;
        }
        $partComplementaries['introduction'] = $results['introduction']->getContent();

        return $partComplementaries;
    }

    /**
     * <p>
     *     Returns
     *     <ul>
     *         <li>the course with complementaries</li>
     *         <ul>
     *             <li>category</li>
     *             <li>metadatas</li>
     *             <li>tags</li>
     *             <li>toc</li>
     *         </ul>
     *         <li>the part with the part complementaries</li>
     *         <ul>
     *             <li>metadatas</li>
     *             <li>tags</li>
     *             <li>introduction</li>
     *         </ul>
     *     </ul>
     * </p>
     *
     * @param mixed $categoryIdentifier The category id | slug
     * @param mixed $courseIdentifier   The course id | slug
     * @param mixed $partIdentifier     The part id | slug
     *
     * @return array : <ul>
     *                     <li>course</li>
     *                     <li>part</li>
     *                 </ul>
     */
    public function findPartWithComplementaries($categoryIdentifier, $courseIdentifier,
                    $partIdentifier)
    {
        /*
         * REQUESTS
         */
        /* Get course and category */
        $requests['course'] = CourseRepository::findRequest($courseIdentifier);
        $requests['category'] = CategoryRepository::findRequest($categoryIdentifier);
        /* Get course complementaries */
        $requests['courseMetadatas'] = CourseRepository::findCourseMetadatasRequest(
            $courseIdentifier);
        $requests['courseTags'] = CourseRepository::findCourseTagsRequest($courseIdentifier);
        $requests['toc'] = CourseRepository::findCourseTocRequest($courseIdentifier);
        /* Get part */
        $requests['part'] = self::findRequest($courseIdentifier, $partIdentifier);
        /* Get part complementaries */
        $requests['partMetadatas'] = self::findPartMetadatasRequest($courseIdentifier,
            $partIdentifier);
        $requests['partTags'] = self::findPartTagsRequest($courseIdentifier, $partIdentifier);
        $requests['introduction'] = self::findPartIntroductionRequest($courseIdentifier,
            $partIdentifier);

        $results = $this->claireApi->getResults($requests);

        /* ****************** *
         * ***** COURSE ***** *
         * ****************** */

        /* Check course and category */
        $course = $results['course']->getContent();
        $category = $results['category']->getContent();

        if (empty($course)) {
            throw new HttpException(404, 'Course '.$courseIdentifier.' does not exist');
        }
        if (empty($category)) {
            throw new HttpException(404, 'Category '.$categoryIdentifier.' does not exist');
        }
        $course = CourseFactory::create($course);
        $category = CategoryFactory::create($category);

        if ($course->getCategory()->getId() != $category->getId()) {
            throw new HttpException(404,
                'Course: '.$courseIdentifier.' is not in category "'.$categoryIdentifier.'"');
        }
        $course->setCategory($category);
        /* Get course complementaries */
        if (!is_null($results['courseMetadatas']->getContent())) {
            $metadatas = MetadataFactory::createCourseMetadataCollection(
                $results['courseMetadatas']->getContent());
            $course->setMetadatas($metadatas);
        }
        if (!is_null($results['courseTags']->getContent())) {
            $tags = TagFactory::createCollection($results['courseTags']->getContent());
            $course->setTags($tags);
        }
        $toc = $results['toc']->getContent();
        if (!empty($toc)) {
            $toc = TocFactory::create($toc);
            $course->setToc($toc);
        }

        /* **************** *
         * ***** PART ***** *
         * **************** */

        /* Get part */
        $partResult = $results['part']->getContent();
        if (is_null($partResult)) {
            throw new HttpException(404, 'Part '.$partIdentifier.' does not exist');
        }
        $part = PartFactory::create($partResult);
        /* Get part complementaries */
        if (!is_null($results['partMetadatas']->getContent())) {
            $metadatas = MetadataFactory::createCourseMetadataCollection(
                $results['partMetadatas']->getContent());
            $part->setMetadatas($metadatas);
        }
        $partTags = $results['partTags']->getContent();
        if (!empty($partTags)) {
            $tags = TagFactory::createCollection($partTags);
            $partComplementaries['partTags'] = $tags;
        }
        $part->setIntroduction($results['introduction']->getContent());

        return array('course'=> $course, 'part'=> $part);
    }

    /* **************************** *
     *                              *
     * ********* REQUESTS ********* *
     *                              *
     * **************************** */
    /**
     * Returns the part (ApiRequest)
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param mixed  $partIdentifier   The part id | slug
     * @param string $format           The requested format
     *
     * @return ApiRequest
     */
    public static function findRequest($courseIdentifier, $partIdentifier, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_PART.$partIdentifier);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        $apiRequestOptions = new ApiRequestOptions();
        $apiRequestOptions->setFormat($format);

        $apiRequest->setOptions($apiRequestOptions);
        return $apiRequest;
    }

    /**
     * Returns metadatas for a course (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return ApiRequest
     */
    public static function findPartMetadatasRequest($courseIdentifier, $partIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest
            ->setBaseUrl(
                self::URL_COURSES.$courseIdentifier.self::URL_PART.$partIdentifier.self::URL_PART_METADATAS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns the tags of a part (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return ApiRequest
     */
    public static function findPartTagsRequest($courseIdentifier, $partIdentifier)
    {
        $apiRequest = new ApiRequest();

        $apiRequest
            ->setBaseUrl(
                self::URL_COURSES.$courseIdentifier.self::URL_PART.$partIdentifier.self::URL_PART_TAGS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns a part introduction (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return ApiRequest
     */
    public static function findPartIntroductionRequest($courseIdentifier, $partIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest
            ->setBaseUrl(
                self::URL_COURSES.$courseIdentifier.self::URL_PART.$partIdentifier.self::URL_PART_INTRODUCTION);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        $apiRequestOptions = new ApiRequestOptions();
        $apiRequestOptions->setFormat('text/html');
        $apiRequest->setOptions($apiRequestOptions);

        return $apiRequest;
    }
}
