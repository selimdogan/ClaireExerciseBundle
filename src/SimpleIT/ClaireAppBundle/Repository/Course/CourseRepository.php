<?php
namespace SimpleIT\ClaireAppBundle\Repository\Course;
use SimpleIT\AppBundle\Services\ApiService;

use SimpleIT\ClaireAppBundle\Model\CategoryFactory;

use Symfony\Component\HttpKernel\Exception\HttpException;

use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;

use SimpleIT\ClaireAppBundle\Repository\User\AuthorRepository;

use SimpleIT\ClaireAppBundle\Model\AuthorFactory;

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
 * Class CourseRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseRepository extends ApiRouteService
{
    /** @var ClaireApi The Claire Api */
    private $claireApi;

    /** The base url for courses = '/courses/' */
    const URL_COURSES = '/courses/';

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
     /**
      * Returns a course
      *
      * @param mixed $courseIdentifier The course id | slug
      *
      * @return Course The course
      */
     public function find($courseIdentifier)
     {
         $courseRequest = $this->findRequest($courseIdentifier);

         $courseResult = $this->claireApi->getResult($courseRequest);

         ApiService::checkResponseSuccessful($courseResult);
         $course = CourseFactory::create($courseResult->getContent());

         return $course;
     }

// FIXME Delete above
//     /**
//      * <p>
//      *     Returns the course complementaries
//      *     <ul>
//      *         <li>metadatas</li>
//      *         <li>tags</li>
//      *         <li>introduction</li>
//      *         <li>toc</li>
//      *     </ul>
//      * </p>
//      *
//      * @param mixed $courseIdentifier The course id | slug
//      *
//      * @return array Course complementaries
//      *
//      * @deprecated
//      */
//     public function findCourseComplementaries($courseIdentifier)
//     {
//         $requests['metadatas'] = $this->findCourseMetadatasRequest($courseIdentifier);
//         $requests['tags'] = $this->findCourseTagsRequest($courseIdentifier);
//         $requests['introduction'] = $this->findCourseIntroductionRequest($courseIdentifier);
//         $requests['toc'] = $this->findCourseTocRequest($courseIdentifier);

//         $results = $this->claireApi->getResults($requests);

//         if (!is_null($results['metadatas']->getContent())) {
//             $metadatas = MetadataFactory::createCourseMetadataCollection(
//                 $results['metadatas']->getContent());
//             $courseComplementaries['metadatas'] = $metadatas;
//         }
//         if (!is_null($results['tags']->getContent())) {
//             $tags = TagFactory::createCollection($results['tags']->getContent());
//             $courseComplementaries['tags'] = $tags;
//         }
//         $courseComplementaries['introduction'] = $results['introduction']->getContent();

//         if (!is_null($results['toc']->getContent())) {
//             $toc = TocFactory::create($results['toc']->getContent());
//             $courseComplementaries['toc'] = $toc;
//         }

//         return $courseComplementaries;
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

        if ($course->getCategory()->getId() != $category->getId()) {
            throw new HttpException(404, 'Course: '.$courseIdentifier.' is not in category "'.$categoryIdentifier.'"');
        }

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
            $toc = TocFactory::create($results['toc']->getContent());
            $course->setToc($toc);
        }

        if (ApiService::isResponseSuccessful($results['authors'])) {
            $authors = AuthorFactory::createCollection($results['authors']->getContent());
            $course->setAuthors($authors);
        }

        return $course;
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
