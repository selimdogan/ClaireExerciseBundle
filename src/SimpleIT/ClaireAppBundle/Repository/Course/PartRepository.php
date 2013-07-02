<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\AppBundle\Services\ApiService;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Model\AuthorFactory;
use SimpleIT\ClaireAppBundle\Model\CategoryFactory;
use SimpleIT\ClaireAppBundle\Model\Course\Part;
use SimpleIT\ClaireAppBundle\Model\CourseFactory;
use SimpleIT\ClaireAppBundle\Model\MetadataFactory;
use SimpleIT\ClaireAppBundle\Model\PartFactory;
use SimpleIT\ClaireAppBundle\Model\TagFactory;
use SimpleIT\ClaireAppBundle\Model\TocFactory;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartRepository extends AppRepository
{

    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\PartResource';

    /**
     * Find a part
     *
     * @param string $courseIdentifier Course id | slug
     * @param string $partIdentifier   Part id | slug
     *
     * @return PartResource
     */
    public function find($courseIdentifier, $partIdentifier)
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier)
        );
    }

    /**
     * Update a part
     *
     * @param string       $courseIdentifier Course id | slug
     * @param string       $partIdentifier   Part id | slug
     * @param PartResource $part             Part
     *
     * @return PartResource
     */
    public function update($courseIdentifier, $partIdentifier, PartResource $part)
    {
        return $this->updateResource(
            $part,
            array('courseIdentifier' => $courseIdentifier, 'partIdentifier' => $partIdentifier)
        );
    }

    /* **********************
     *
     * ***** OLD SHIT ***** *
     *
     * **********************/
   /** @var ClaireApi The Claire Api */
    protected $claireApi;

    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses/';

    /** The base url for parts = '/parts/' */
    const URL_PART = '/parts/';

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

     public function updatePartContent($courseIdentifier, $partIdentifier, $content)
     {
         $partRequest = self::updateRequest($courseIdentifier, $partIdentifier, $content, ApiRequest::FORMAT_HTML);
         $partResult = $this->claireApi->getResult($partRequest);
         $partRequest = self::findRequest($courseIdentifier, $partIdentifier);
         $partResult = $this->claireApi->getResult($partRequest);

         $part = PartFactory::create($partResult->getContent());
         return $part;
     }

     /**
      * Returns a part
      *
      * @param mixed $courseIdentifier The course id | slug
      * @param mixed $partIdentifier   The part id | slug
      *
      * @return Part The part
      */
     public function findPart($courseIdentifier, $partIdentifier)
     {
         $partRequest = self::findRequest($courseIdentifier, $partIdentifier);

         $partResult = $this->claireApi->getResult($partRequest);
         $part = PartFactory::create($partResult->getContent());

         return $part;
     }

     /**
      * Returns a part introduction
      *
      * @param mixed  $courseIdentifier The course id | slug
      * @param mixed  $partIdentifier   The part id | slug
      * @param string $format           The requested format
      *
      * @return string The part introduction
      */
     public function findIntroduction($courseIdentifier, $partIdentifier)
     {
         $partRequest = self::findPartIntroductionRequest($courseIdentifier, $partIdentifier);

         $partResult = $this->claireApi->getResult($partRequest);

         try {
            ApiService::checkResponseSuccessful($partResult);
         } catch (HttpException $e) {
            return null;
         }

         return $partResult->getContent();
     }


    /**
     * Returns a part content
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param mixed  $partIdentifier   The part id | slug
     * @param string $format           The requested format
     *
     * @return Part The part content
     */
    public function findContent($courseIdentifier, $partIdentifier, $format = null)
    {
        $partRequest = self::findContentRequest($courseIdentifier, $partIdentifier, $format);

        $partResult = $this->claireApi->getResult($partRequest);
        ApiService::checkResponseSuccessful($partResult);

        return $partResult->getContent();
    }

//     /**
//      * <p>
//      *     Returns the part complementaries
//      *     <ul>
//      *         <li>metadatas</li>
//      *         <li>tags</li>
//      *         <li>introduction</li>
//      *     </ul>
//      * </p>
//      *
//      * @param mixed $courseIdentifier The course id | slug
//      * @param mixed $partIdentifier   The part id | slug
//      *
//      * @return array Part complementaries
//      * @deprecated
//      */
//     public function findPartComplementaries($courseIdentifier, $partIdentifier)
//     {
//         $requests['metadatas'] = $this
//             ->findPartMetadatasRequest($courseIdentifier, $partIdentifier);
//         $requests['tags'] = self::findPartTagsRequest($courseIdentifier, $partIdentifier);
//         $requests['introduction'] = $this
//             ->findPartIntroductionRequest($courseIdentifier, $partIdentifier);

//         $results = $this->claireApi->getResults($requests);

//         if (!is_null($results['metadatas']->getContent())) {
//             $metadatas = MetadataFactory::createCourseMetadataCollection(
//                 $results['metadatas']->getContent());
//             $partComplementaries['metadatas'] = $metadatas;

//         }
//         if (!is_null($results['tags']->getContent())) {
//             $tags = TagFactory::createCollection($results['tags']->getContent());
//             $partComplementaries['tags'] = $tags;
//         }
//         $partComplementaries['introduction'] = $results['introduction']->getContent();

//         return $partComplementaries;
//     }

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
        $requests['courseAuthors'] = CourseRepository::findCourseAuthorsRequest($courseIdentifier);
        $requests['toc'] = CourseRepository::findCourseTocRequest($courseIdentifier);
        /* Get part */
        $requests['part'] = self::findRequest($courseIdentifier, $partIdentifier);
        /* Get part complementaries */
        $requests['partMetadatas'] = self::findPartMetadatasRequest($courseIdentifier,
            $partIdentifier);
//      $requests['partTags'] = self::findPartTagsRequest($courseIdentifier, $partIdentifier);
//         $requests['introduction'] = self::findPartIntroductionRequest($courseIdentifier,
//             $partIdentifier);

        $results = $this->claireApi->getResults($requests);

        /* ****************** *
         * ***** COURSE ***** *
         * ****************** */

        /* Check course and category */
        ApiService::checkResponseSuccessful($results['category']);
        ApiService::checkResponseSuccessful($results['course']);
        ApiService::checkResponseSuccessful($results['part']);

        $course = $results['course']->getContent();
        $category = $results['category']->getContent();

        $course = CourseFactory::create($course);
        $category = CategoryFactory::create($category);

        $course->setCategory($category);

        /* Get course complementaries */
        if (ApiService::isResponseSuccessful($results['courseMetadatas'])) {
            $metadatas = MetadataFactory::createCourseMetadataCollection(
                $results['courseMetadatas']->getContent());
            $course->setMetadatas($metadatas);
        }
        if (ApiService::isResponseSuccessful($results['courseTags'])) {
            $tags = TagFactory::createCollection($results['courseTags']->getContent());
            $course->setTags($tags);
        }

        if (ApiService::isResponseSuccessful($results['toc'])) {
            $data = $this->lineariesToc($results['toc']->getContent());
            $toc = TocFactory::create($data);
            $course->setToc($toc);
        }
        if (ApiService::isResponseSuccessful($results['courseAuthors'])) {
            $authors = AuthorFactory::createCollection(
                $results['courseAuthors']->getContent());
            $course->setAuthors($authors);
        }

        /* **************** *
         * ***** PART ***** *
         * **************** */

        /* Get part */
        $part = PartFactory::create($results['part']->getContent());

        /* Get part complementaries */
        if (ApiService::isResponseSuccessful($results['partMetadatas'])) {
            $metadatas = MetadataFactory::createCourseMetadataCollection(
                $results['partMetadatas']->getContent());
            $part->setMetadatas($metadatas);
        }
//        if (ApiService::isResponseSuccessful($results['partTags'])) {
//            $tags = TagFactory::createCollection($results['partTags']->getContent());
//            $part->setTags($tags);
//        }
//         if (ApiService::isResponseSuccessful($results['introduction'])) {
//             $part->setIntroduction($results['introduction']->getContent());
//         }

        return array('course'=> $course, 'part'=> $part);
    }

    private function lineariesToc($toc)
    {
        $lineare = array();
        $linearies = function ($toc) use (&$linearies, $lineare) {
            $lineare[] = $toc;

            foreach ($toc['children'] as $child) {
                $lineare[] = $child;
                $linearies($child);
            }
        };

        return $lineare;
    }

    /* **************************** *
     *                              *
     * ********* REQUESTS ********* *
     *                              *
     * **************************** */

    public static function updateRequest($courseIdentifier, $partIdentifier, $content, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_PART.$partIdentifier);
        $apiRequest->addFilter('isSubPart', 'true');
        $apiRequest->setMethod(ApiRequest::METHOD_PUT);
        $apiRequest->setParameters(array('content' => $content));

        $apiRequestOptions = new ApiRequestOptions();
        $apiRequestOptions->setFormat($format);

        $apiRequest->setOptions($apiRequestOptions);
        return $apiRequest;
    }

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
        $apiRequest->setFormat($format);

        return $apiRequest;
    }

    /**
     * Returns the part (ApiRequest)
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param mixed  $partIdentifier   The part id | slug
     * @param string $format           The requested format
     *
     * @return ApiRequest
     */
    public static function findContentRequest($courseIdentifier, $partIdentifier, $format = null)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_PART.$partIdentifier.'/content');
        $apiRequest->setMethod(ApiRequest::METHOD_GET);
        $apiRequest->setFormat($format);

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
        $apiRequestOptions->setFormat(ApiRequest::FORMAT_HTML);
        $apiRequest->setOptions($apiRequestOptions);

        return $apiRequest;
    }
}
