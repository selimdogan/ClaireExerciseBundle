<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\AppBundle\Model\ApiRequest;
use SimpleIT\AppBundle\Services\ApiService;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\ClaireAppBundle\Services\CourseService;

/**
 * Part controller
 *
 * The part controller is used to handle all the actions for
 * the parts of a course
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartController extends BaseController
{
    /** @var Service The course service*/
    private $courseService;

    /**
     * Show a part
     *
     * @param Request $request      The request
     * @param string  $categorySlug The slug of the category
     * @param string  $courseSlug   The slug of the course
     * @param string  $partSlug     The slug of the part
     *
     * @return Response
     */
    public function readAction(Request $request, $categorySlug, $courseSlug,
                    $partSlug)
    {
        //TODO Check if faster if category and course are in the same request
        /* Get the category */
        $categoryRequest = $this->getClaireApi('categories')
            ->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);
        ApiService::checkResponseSuccessful($category);

        /* Get the course */
        $courseRequest = $this->getClaireApi('courses')->getCourse($courseSlug);
        $course = $this->getClaireApi()->getResult($courseRequest);
        ApiService::checkResponseSuccessful($course);

        /* Get the course service */
        $this->courseService = $this->get('simpleit.claire.course');

        /* Check if the course is in the requested category */
        $this->courseService->checkCourseInCategory($course, $category);

        $displayLevel = $course['displayLevel'];

        /* Get the Part */
        //FIXME ApiResult
        $partRequest = $this->getClaireApi('parts')
            ->getPart($courseSlug, $partSlug);
        $part = $this->getClaireApi()->getResult($partRequest);
        ApiService::checkResponseSuccessful($part);

        /* Get the Part content (only for 1b or 2c) */
        //FIXME ApiResult
        //TODO Constantes
        if ($displayLevel === 1 || $part['type'] == 'title-3') {
            $options = new ApiRequestOptions();
            $options->setFormat(ApiRequest::FORMAT_HTML);
            $requests['content'] = $this->getClaireApi('parts')
                ->getPart($courseSlug, $partSlug, $options);
        }
        if ($displayLevel === 2 && $part['type'] == 'title-3') {

            //TODO
            //             $requests['parentPart'] = $this->getClaireApi('parts')
            //                 ->getParentPart($courseSlug, $partSlug, $options);
        }

        /* ********************** *
         * ***** Requesting ***** *
         * ********************** */

        $requests['courseToc'] = $this->getClaireApi('courses')
            ->getCourseToc($courseSlug);
        $requests['partIntroduction'] = $this->getClaireApi('parts')
            ->getIntroduction($courseSlug, $partSlug);

        $requests['courseTags'] = $this->getClaireApi('courses')
            ->getCourseTags($courseSlug);
        $requests['partTags'] = $this->getClaireApi('parts')
            ->getPartTags($courseSlug, $partSlug);
        //FIXME create route
        $parentPartTags = null;

        $requests['courseMetadatas'] = $this->getClaireApi('courses')
            ->getCourseMetadatas($courseSlug);
        $requests['partMetadatas'] = $this->getClaireApi('parts')
            ->getPartMetadatas($courseSlug, $partSlug);

        /* Flush the requests */
        $results = $this->getClaireApi()->getResults($requests);

        /* ********************* *
         * ***** Resulting ***** *
         * ********************* */

        /* Get the toc */
        $toc = $results['courseToc'];
        /* Get the introduction */
        $introduction = $results['partIntroduction'];
        /* Get tags */
        $courseTags = $results['courseTags'];
        $partTags = $results['partTags'];
        /* Get the metadatas */
        $courseMetadatas = $results['courseMetadatas'];
        $partMetadatas = $results['partMetadatas'];
        //FIXME
        $parentPartMetadatas = null;
        /* Get the content */
        $content = null;
        if (array_key_exists('content', $results)) {
            $content = $results['content'];
        }

        //TODO flush on Api
        //$this->getClaireApi()->flush();

        /* ******************************* *
         * ***** PREPARE THE CONTENT ***** *
         * ******************************* */

        //FIXME tags service

        /* Format the tags */
        $tags = $this->courseService
            ->getPartTags($courseTags, $partTags, $parentPartTags);
        /* Format the metadatas */
        $formatedMetadatas = $this->courseService
            ->getFormatedPartMetadatas($courseMetadatas, $partMetadatas,
                $parentPartMetadatas);
        /* Format the content */
        $formatedContent = null;
        if (null != $content) {
            $formatedContent = $this->courseService
            ->getFormatedContent($content->getContent());
        }
        /* Get the pagination */
        $pagination = $this->courseService
            ->getPagination($toc->getContent(), $part, $displayLevel);

        return $this
            ->render($this->getView($displayLevel, $part['type']),
                array('title' => $part['title'], 'course' => $course,
                'part' => $part, 'category' => $category,
                'icon' => ArrayUtils::getValue($formatedMetadatas,
                    CourseService::COURSE_METADATA_ICON),
                'aggregateRating' => ArrayUtils::getValue(
                    (array) $formatedMetadatas,
                    CourseService::COURSE_METADATA_AGGREGATE_RATING),
                'difficulty' => ArrayUtils::getValue(
                    (array) $formatedMetadatas,
                    CourseService::COURSE_METADATA_DIFFICULTY),
                'duration' => ArrayUtils::getValue($formatedMetadatas,
                    CourseService::COURSE_METADATA_DURATION),
                'timeline' => $this->courseService
                    ->formatTimeline($toc, $displayLevel, $part),
                'tags' => $tags,
                'updatedAt' => new \DateTime($part['updatedAt']),
                'pagination' => $pagination,
                'introduction' => $introduction->getContent(),
                'toc' => $this->courseService
                    ->getDisplayToc($toc, $displayLevel),
                'contentHtml' => $formatedContent,
                'license' => ArrayUtils::getValue((array) $formatedMetadatas,
                    CourseService::COURSE_METADATA_LICENSE),
                'description' => ArrayUtils::getValue(
                    (array) $formatedMetadatas,
                    CourseService::COURSE_METADATA_DESCRIPTION)
                ));
    }

    /**
     * Get the associated view for a specific context
     *
     * @param integer $displayLevel The level display for the course
     *                              Should be 1 or 2
     * @param string  $type         The type of the part
     *
     * @return string The associated view
     */
    private function getView($displayLevel, $type)
    {
        $this->courseService->checkPartDisplayLevelValidity($displayLevel);

        //FIXME constants
        if ($displayLevel == 1 && $type == 'title-1') {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        } elseif ($displayLevel == 2 && $type == 'title-2') {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2 && $type == 'title-3') {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        }
        return $view;
    }

    //     /**
    //      * Make Breadcrumb
    //      *
    //      * @param array $baseCourse Base Course
    //      * @param array $category   Category
    //      * @param array $course     Course
    //      * @param array $toc        TOC
    //      */
    //     private function makeBreadcrumb($category, $course, $part, $toc)
    //     {
    //         $points = array('course' => 0, 'title-1' => 1, 'title-2' => 2,
    //         'title-3' => 3,
    //         );

    //         // BreadCrumb
    //         $breadcrumb = $this->get('apy_breadcrumb_trail');
    //         $breadcrumb
    //             ->add($category['title'], 'SimpleIT_Claire_categories_view',
    //                 array('slug' => $category['slug']));

    //         $breadcrumb
    //             ->add($course['title'], 'course_view',
    //                 array('categorySlug' => $category['slug'],
    //                 'courseSlug' => $course['slug']
    //                 ));

    //         if ($course['displayLevel'] == 2 && !empty($toc)) {
    //             //TODO
    //         }
    //        if (!empty($toc))
    //        {
    //            foreach($toc as $key => $element)
    //            {
    //                if ($element['slug'] == $course['slug'])
    //                {
    //                    $types = array('title-1', $element['type']);
    //                    for($i = $key - 1; $i >= 0; $i--)
    //                    {
    //                        if (!in_array($toc[$i]['type'], $types) && $points[$toc[$i]['type']] < $points[$element['type']])
    //                        {
    //                            $types[] = $toc[$i]['type'];
    //                            $breadcrumb->add($toc[$i]['title'],
    //                                    'course_view',
    //                                    array(
    //                                        'categorySlug' => $category['slug'],
    //                                        'rootSlug'     => $baseCourse['slug'],
    //                                        'titleSlug'    => $toc[$i]['slug']
    //                                        )
    //                                    );
    //                        }
    //                    }
    //                    break;
    //                }
    //            }
    //        }
    //         $breadcrumb->add($part['title']);
    //     }

    //     /**
    //      * List courses
    //      *
    //      * @return Response
    //      */
    //     public function listAction(Request $request)
    //     {
    //         $parameters = $request->query->all();

    //         $options = new ApiRequestOptions();
    //         $options->setItemsPerPage(18);
    //         $options->setPageNumber($request->get('page', 1));
    //         $options->bindFilter($parameters, array('sort'));

    //         $coursesRequest = $this->getClaireApi('courses')->getCourses($options);
    //         $courses = $this->getClaireApi()->getResult($coursesRequest);

    //         $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
    //         $this->viewParameters = array('courses' => $courses->getContent(),
    //         'appPager' => $courses->getPager()
    //         );
    //         return $this->generateView($this->view, $this->viewParameters);
    //     }

    /**
     * Generate the rendered response
     *
     * @param string $view           The view name
     * @param array  $viewParameters Associated view parameters
     *
     * @return Response A Response instance
     */
    protected function generateView($view, $viewParameters)
    {
        return $this->render($view, $viewParameters);
    }
}
