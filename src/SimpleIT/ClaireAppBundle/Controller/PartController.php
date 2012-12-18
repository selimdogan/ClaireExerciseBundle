<?php
namespace SimpleIT\ClaireAppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Controller\BaseController;
use SimpleIT\ClaireAppBundle\Form\Type\CourseType;
use SimpleIT\AppBundle\Model\ApiRequestOptions;
use SimpleIT\AppBundle\Model\ApiRequest;

/**
 * Part controller
 */
class PartController extends BaseController
{
    /**
     * View a course
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function readAction(Request $request, $categorySlug, $courseSlug, $partSlug)
    {
        $configuration = array(
            '1' => array(null, 'title-1'),
            '2' => array(null, 'title-2', 'title-3')
        );

        // Category API
        $categoryRequest = $this->getClaireApi('categories')->getCategory($categorySlug);
        $category = $this->getClaireApi()->getResult($categoryRequest);
        $this->checkObjectFound($category);

        // Course API
        $courseRequest = $this->getClaireApi('courses')->getCourse($courseSlug);
        $course = $this->getClaireApi()->getResult($courseRequest);
        $this->checkObjectFound($course);

        // Check category
        $category = $category->getContent();
        $course = $course->getContent();
        if($course['category']['id'] != $category['id'])
        {
            throw $this->createNotFoundException('Unable to find this course in this category');
        }

        // Course API
        $partRequest = $this->getClaireApi('parts')->getPart($courseSlug, $partSlug);
        $part = $this->getClaireApi()->getResult($partRequest);
        $this->checkObjectFound($part);
        $part = $part->getContent();

        $titleType = $part['type'];

        // Requesting
        $options = new ApiRequestOptions();
        $options->setFormat(ApiRequest::FORMAT_HTML);
        $requests['content'] = $this->getClaireApi('parts')->getPart($courseSlug, $partSlug, $options);
        $requests['courseToc'] = $this->getClaireApi('courses')->getCourseToc($courseSlug);
        $requests['partIntroduction'] = $this->getClaireApi('parts')->getIntroduction($courseSlug, $partSlug);
        $requests['partTags'] = $this->getClaireApi('parts')->getPartTags($courseSlug, $partSlug);
        $requests['partMetadatas'] = $this->getClaireApi('parts')->getPartMetadatas($courseSlug, $partSlug);
        $requests['courseTags'] = $this->getClaireApi('courses')->getCourseTags($courseSlug);
        $requests['courseMetadatas'] = $this->getClaireApi('courses')->getCourseMetadatas($courseSlug);

        $results = $this->getClaireApi()->getResults($requests);

        $toc = $results['courseToc']->getContent();
        $content = $results['content']->getContent();
        $introduction = $results['partIntroduction']->getContent();

        // Check metadatas
        // @TODO
        $tags = $results['partTags']->getContent();
        $metadatas = $results['partMetadatas']->getContent();
//        $tags = if $this->getTags($results['partTags']->getContent(), $results['courseTags']->getContent());
//        $metadatas = $this->getMetadatas($results['partMetadatas']->getContent(), $results['courseMetadatas']->getContent());

        $date = new \DateTime();
        $part['updatedAt'] = $date->setTimestamp(strtotime($part['updatedAt']));

        // Alterate
        $pagination = $this->get('simpleit.claire.course')->setPagination(
            $part,
            $toc,
            ($course['displayLevel'] == 1) ? array('title-2', 'title-3') : array('title-1')
        );

        /* Get timeline*/
        $timeline = $this->prepareTimeline($toc, $course['displayLevel']);

        // Breadcrumb
        $this->makeBreadcrumb(
                $category,
                $course,
                $part,
                $toc);

        // Restrict TOC
        $toc = $this->get('simpleit.claire.course')->restrictTocForTitle(
                $part,
                $toc,
                (is_null($titleType) && $course['displayLevel'] == 1)  ? 'course' : $titleType);

        /* If part doesn't have any tags, use the course's tags */
        if (empty($tags)) {
            $tags = $results['courseTags']->getContent();
        }
        /* If part doesn't have any difficulty, use the course's difficulty */
        $difficulty =  $this->getOneMetadata('difficulty', $metadatas);
        if ($difficulty == '') {
            $difficulty = $this->getOneMetadata('difficulty', $results['courseMetadatas']->getContent());
        }
        //FIXME
        $content = preg_replace('/<h1(.*)h1>/', '', $content);

        return $this->render($this->getView($part['displayLevel'], $titleType),
            array(
                'course' => $course,
                'part' => $part,
                'introduction' => $introduction,
                'toc' => $toc,
                'tags' => $tags,
                'contentHtml' => $content,
                'timeline' => $timeline,
                'rootSlug' => $courseSlug,
                'category' => $category,
                'difficulty' => $difficulty,
                'duration' => $this->getOneMetadata('duration', $metadatas),
                'licence' => $this->getOneMetadata('license', $metadatas),
                'description' => $this->getOneMetadata('description ', $metadatas),
                'rate' => $this->getOneMetadata('aggregateRating', $metadatas),
                'icon' => $this->getOneMetadata('image', $metadatas),
                'updatedAt'=> $part['updatedAt'],
                'titleType' => $titleType,
                'pagination' => $pagination
            )
        );
    }

    private function getView($displayLevel, $type)
    {
        if($displayLevel == 1 && $type == 'title-1')
        {
            $view = 'TutorialBundle:Tutorial:view1b.html.twig';
        }
        elseif($displayLevel == 2 && $type == 'title-2')
        {
            $view = 'TutorialBundle:Tutorial:view2b.html.twig';
        }
        elseif($displayLevel == 2 && $type == 'title-3')
        {
            $view = 'TutorialBundle:Tutorial:view2c.html.twig';
        }

        return $view;
    }

        private function prepareTimeline($toc, $displayLevel){
         if ($displayLevel == 0 || $displayLevel == 1)
        {
            $timeline = array();
            $i = 0;
            foreach ($toc as $part)
            {
                if ($part['type'] == 'title-1')
                {
                    $timeline[$i] = $part;
                    $i++;
                }
            }
        }
        return $timeline;
    }

    /**
     * Make Breadcrumb
     *
     * @param array $baseCourse Base Course
     * @param array $category   Category
     * @param array $course     Course
     * @param array $toc        TOC
     */
    private function makeBreadcrumb($category,$course, $part, $toc)
    {
       $points = array(
            'course' => 0,
            'title-1' => 1,
            'title-2' => 2,
            'title-3' => 3,
        );

        // BreadCrumb
        $breadcrumb = $this->get('apy_breadcrumb_trail');
        $breadcrumb->add($category['title'], 'SimpleIT_Claire_categories_view', array('slug' => $category['slug']));

        $breadcrumb->add($course['title'], 'course_view',array('categorySlug' => $category['slug'],
            'courseSlug'=> $course['slug']));

        if ($course['displayLevel'] == 2 && !empty($toc)){
            //TODO
        }
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
        $breadcrumb->add($part['title']);
    }

    /**
     * Get One metadata
     *
     * @param string $key  Key to search
     * @param array  $list Array list of metadata
     *
     * @return string | null
     */
    private function getOneMetadata($key, $metadatas)
    {
        $value = '';

        if (is_array($metadatas))
        {
            foreach($metadatas as $metadata)
            {
                if ($metadata['key'] == $key)
                {
                    $value = $metadata['value'];
                    break;
                }
            }
        }

        return $value;
    }

    /**
     * List courses
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $parameters = $request->query->all();

        $options = new ApiRequestOptions();
        $options->setItemsPerPage(18);
        $options->setPageNumber($request->get('page', 1));
        $options->bindFilter($parameters, array('sort'));

        $coursesRequest = $this->getClaireApi('courses')->getCourses($options);
        $courses = $this->getClaireApi()->getResult($coursesRequest);

        $this->view = 'SimpleITClaireAppBundle:Course:list.html.twig';
        $this->viewParameters = array(
            'courses' => $courses->getContent(),
            'appPager' => $courses->getPager()
        );
        return $this->generateView($this->view, $this->viewParameters);
    }

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
