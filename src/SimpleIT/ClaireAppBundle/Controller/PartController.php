<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use SimpleIT\ClaireAppBundle\Model\Course\Course;

use SimpleIT\ClaireAppBundle\Model\Metadata;

use SimpleIT\ClaireAppBundle\Model\Course\Part;

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
    public function partAction(Request $request, $categorySlug, $courseSlug, $partSlug)
    {
        $this->courseService = $this->get('simpleit.claire.course');

        $data = $this->courseService->getPartWithComplementaries($categorySlug, $courseSlug, $partSlug);
        $course = $data['course'];
        $part = $data['part'];
        $timeline = $this->courseService->getTimeline($course);

        $displayLevel = $course->getDisplayLevel();
        /* Get the Part content (only for 1b or 2c) */
        $formatedContent = null;
        if ($displayLevel === 1 || Part::TYPE_TITLE_3 === $part->getType()) {
            $content = $this->courseService->getPartContent($courseSlug, $partSlug);
            if (null != $content) {
                $formatedContent = $this->courseService->getFormatedContent($content);
            }
        }
        //TODO Api Route
        $parentPart = null;
        if ($displayLevel === 2 && Part::TYPE_TITLE_3 === $part->getType()) {
            //$parentPart = $this->courseService->getParentPart($part);
        }

        /* Format the tags */
        $tags = $this->courseService->getPartTags($course, $part, $parentPart);

        /* Format the metadatas */
        $metadatas = $this->courseService->getPartMetadatas($course, $part, $parentPart);

        /* Get the pagination */
        $pagination = $this->courseService->getPagination($course, $part, $displayLevel);

        return $this
            ->render($this->getView($displayLevel, $part->getType()),
                array('title' => $part->getTitle(), 'course' => $course, 'part' => $part,
                'category' => $course->getCategory(),
                'icon' => ArrayUtils::getValue($metadatas, Metadata::COURSE_METADATA_IMAGE),
                'aggregateRating' => ArrayUtils::getValue($metadatas,
                    Metadata::COURSE_METADATA_AGGREGATE_RATING),
                'difficulty' => ArrayUtils::getValue($metadatas,
                    Metadata::COURSE_METADATA_DIFFICULTY),
                //FIXME DateInterval
                'duration' => ArrayUtils::getValue($metadatas, Metadata::COURSE_METADATA_DURATION),
                'timeline' => $this->courseService->getTimeline($course), 'tags' => $tags,
                'updatedAt' => $part->getUpdatedAt(), 'pagination' => $pagination,
                'introduction' => $part->getIntroduction(),
                'toc' => $this->courseService->getDisplayToc($course, $displayLevel, $part),
                'contentHtml' => $formatedContent,
                'license' => ArrayUtils::getValue((array) $metadatas,
                    Metadata::COURSE_METADATA_LICENSE),
                'description' => ArrayUtils::getValue((array) $metadatas,
                    Metadata::COURSE_METADATA_DESCRIPTION)
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
    private function getPartView($displayLevel, $type)
    {
        $this->courseService->checkPartDisplayLevelValidity($displayLevel);

        if ($displayLevel == 1 && $type == Part::TYPE_TITLE_1) {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        } elseif ($displayLevel == 2 && $type == Part::TYPE_TITLE_2) {
            $view = 'TutorialBundle:Tutorial:view1a2b.html.twig';
        } elseif ($displayLevel == 2 && $type == Part::TYPE_TITLE_3) {
            $view = 'TutorialBundle:Tutorial:view1b2c.html.twig';
        }
        return $view;
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

