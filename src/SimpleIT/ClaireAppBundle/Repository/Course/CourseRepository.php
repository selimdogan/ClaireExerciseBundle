<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\AppBundle\Services\ApiService;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;
use SimpleIT\ClaireAppBundle\Model\AuthorFactory;
use SimpleIT\ClaireAppBundle\Model\CategoryFactory;
use SimpleIT\ClaireAppBundle\Model\CourseFactory;
use SimpleIT\ClaireAppBundle\Model\MetadataFactory;
use SimpleIT\ClaireAppBundle\Model\TagFactory;
use SimpleIT\ClaireAppBundle\Model\TocFactory;
use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;
use SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class CourseRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Course\CourseResource';

    /**
     * Find a list of courses
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return $this->findAllResources(array(), $collectionInformation);
    }

    /**
     * Find a course
     *
     * @param string $courseIdentifier Course id | slug
     * @param array  $parameters       Parameters
     *
     * @return CourseResource
     * @cache (namespacePrefix="claire_app_course_course", namespaceAttribute="courseIdentifier", lifetime=0)
     */
    public function find($courseIdentifier, array $parameters = array())
    {
        return $this->findResource(
            array('courseIdentifier' => $courseIdentifier),
            $parameters
        );
    }

    /**
     * Update a course
     *
     * @param string         $courseIdentifier Course id | slug
     * @param CourseResource $course           Course
     * @param array          $parameters       Parameters
     *
     * @return CourseResource
     */
    public function update($courseIdentifier, CourseResource $course, array $parameters = array())
    {
        return $this->updateResource(
            $course,
            array('courseIdentifier' => $courseIdentifier,),
            $parameters
        );
    }



    /** @var ClaireApi The Claire Api */
    protected $claireApi;

    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses/';

    const URL_COURSES_CONTENT = '/content';

    /** The url for metadatas  = '/metadatas' */
    const URL_METADATAS = '/metadatas/';

    /** The url for metadatas  = '/tags' */
    const URL_COURSES_TAGS = '/tags/';

    /** The url for introduction = '/introduction' */
    const URL_COURSES_INTRODUCTION = '/introduction';

    /** The url for toc = '/toc' */
    const URL_COURSES_TOC = '/toc';

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
//     /**
//      * Returns a course
//      *
//      * @param mixed $courseIdentifier The course id | slug
//      *
//      * @return Course The course
//      */
//     public function find($courseIdentifier)
//     {
//         $courseRequest = $this->findRequest($courseIdentifier);
//
//         $courseResult = $this->claireApi->getResult($courseRequest);
//
//         ApiService::checkResponseSuccessful($courseResult);
//         $course = CourseFactory::create($courseResult->getContent());
//
//         return $course;
//     }

    /**
     * <p>
     *     Returns the course with complementaries
     *     <ul>
     *         <li>metadatas</li>
     *         <li>tags</li>
     *         <li>introduction</li>
     *         <li>toc</li>
     *     </ul>
     * </p>
     *
     * @param mixed $courseIdentifier   The course id | slug
     * @param mixed $categoryIdentifier The category id | slug
     *
     * @return array Course complementaries
     */
    public function findCourseWithComplementaries($courseIdentifier, $categoryIdentifier)
    {
        $requests['course'] = $this->findRequest($courseIdentifier);
        $requests['content'] = $this->findContentRequest($courseIdentifier);
        $requests['category'] = CategoryRepository::findRequest($categoryIdentifier);
        $requests['metadatas'] = $this->findCourseMetadatasRequest($courseIdentifier);
        $requests['tags'] = $this->findCourseTagsRequest($courseIdentifier);
        $requests['introduction'] = $this->findCourseIntroductionRequest($courseIdentifier);
        $requests['toc'] = $this->findCourseTocRequest($courseIdentifier);
        $requests['authors'] = self::findCourseAuthorsRequest($courseIdentifier);

        $results = $this->claireApi->getResults($requests);

        ApiService::checkResponseSuccessful($results['category']);
        ApiService::checkResponseSuccessful($results['course']);
        $course = CourseFactory::create($results['course']->getContent());
        $category = CategoryFactory::create($results['category']->getContent());

        $course->setCategory($category);

        if (ApiService::isResponseSuccessful($results['metadatas'])) {
            $metadatas = MetadataFactory::createCourseMetadataCollection(
                $results['metadatas']->getContent());
            $course->setMetadatas($metadatas);
        }
        if (ApiService::isResponseSuccessful($results['tags'])) {
            $tags = TagFactory::createCollection($results['tags']->getContent());
            $course->setTags($tags);
        }
        if (ApiService::isResponseSuccessful($results['introduction'])) {
            $course->setIntroduction($results['introduction']->getContent());
        }

        if (ApiService::isResponseSuccessful($results['toc'])) {
            $data = $this->lineariesToc($results['toc']->getContent());
            $toc = TocFactory::create($data);
            $course->setToc($toc);
        }

        if (ApiService::isResponseSuccessful($results['authors'])) {
            $authors = AuthorFactory::createCollection($results['authors']->getContent());
            $course->setAuthors($authors);
        }

        return $course;
    }

    public static function lineariesToc($toc)
    {
        $lineare = array();
        $linearies = function ($toc) use (&$linearies, &$lineare) {
            $lineare[] = $toc;
            if (isset($toc['children'])) {
                foreach ($toc['children'] as $child) {
                    $linearies($child);
                }
            }
        };

        $linearies($toc);

        return $lineare;
    }


    /**
     * Get all courses
     *
     * @param ApiRequestOptions $options The collection options
     *
     * @return Collection
     */
    public function getAll(ApiRequestOptions $options)
    {
        $requests['courses'] = $this->findAll($options);

        $results = $this->claireApi->getResults($requests);

        ApiService::checkResponseSuccessful($results['courses']);

        $courses = CourseFactory::createCollection(
            $results['courses']->getContent());

        return $courses;
    }

    /**
     * Returns a part content
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param string $format           The requested format
     *
     * @return Part The part content
     */
    public function findContent($courseIdentifier, $format = null)
    {
        $request = self::findRequest($courseIdentifier, $format);
        $request->setFormat($format);
        $result = $this->claireApi->getResult($request);
        ApiService::checkResponseSuccessful($result);

        return $result->getContent();
    }

    /* **************************** *
     *                              *
     * ********* REQUESTS ********* *
     *                              *
     * **************************** */
    /**
     * Returns the course (ApiRequest)
     *
     * @param mixed $courseIdentifier The course identifier
     *
     * @return ApiRequest
     */
    public static function findRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    public function findContentRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_COURSES_CONTENT);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        $apiRequestOptions = new ApiRequestOptions();
        $apiRequestOptions->setFormat(ApiRequest::FORMAT_HTML);
        $apiRequest->setOptions($apiRequestOptions);

        return $apiRequest;
    }

//    /**
//     * Returns all the courses (ApiRequest)
//     *
//     * @param ApiRequestOptions $apiRequestOptions List options
//     *
//     * @return ApiRequest
//     */
//    public static function findAll(ApiRequestOptions $apiRequestOptions)
//    {
//        $apiRequest = new ApiRequest();
//        $apiRequest->setBaseUrl(self::URL_COURSES);
//        $apiRequest->setMethod(ApiRequest::METHOD_GET);
//
//        if(!is_null($apiRequestOptions))
//        {
//            $apiRequest->setOptions($apiRequestOptions);
//        }
//
//        return $apiRequest;
//    }

    /**
     * Returns metadatas for a course (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return ApiRequest
     */
    public static function findCourseMetadatasRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_METADATAS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns tags for course (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return ApiRequest
     */
    public static function findCourseTagsRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_COURSES_TAGS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns a course introduction (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return ApiRequest
     */
    public static function findCourseIntroductionRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_COURSES_INTRODUCTION);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        $apiRequestOptions = new ApiRequestOptions();
        $apiRequestOptions->setFormat(ApiRequest::FORMAT_HTML);
        $apiRequest->setOptions($apiRequestOptions);

        return $apiRequest;
    }

    /**
     * Returns a course toc (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return ApiRequest
     */
    public static function findCourseTocRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.self::URL_COURSES_TOC);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }

    /**
     * Returns a course authors (ApiRequest)
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return ApiRequest
     */
    public static function findCourseAuthorsRequest($courseIdentifier)
    {
        $apiRequest = new ApiRequest();
        $apiRequest->setBaseUrl(self::URL_COURSES.$courseIdentifier.AuthorRepository::URL_AUTHORS);
        $apiRequest->setMethod(ApiRequest::METHOD_GET);

        return $apiRequest;
    }
}
