<?php
namespace SimpleIT\ClaireAppBundle\Services;


use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;

use SimpleIT\ClaireAppBundle\Model\CourseFactory;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;

use SimpleIT\ClaireAppBundle\Api;

use SimpleIT\ClaireAppBundle\Model\Course\Course;

use SimpleIT\ClaireAppBundle\Repository\Course\CourseRepository;

use SimpleIT\Utils\ArrayUtils;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\Validator\Constraints\All;

use SimpleIT\AppBundle\Model\ApiResult;

/**
 * CourseService
 */
class CourseService extends ClaireApi implements CourseServiceInterface
{
    /** regex for html part title (<h1>*</h1> */
    const PATTERN_HTML_PART_TITLE = '/<h1(.*)h1>/';

    //TODO Put in Config file
    /** @var array Types needed for the toc level 0 and 1 */
    private $neededTypesLevel1 = array('title-1');

    /** @var array Types needed for the toc level 2a */
    private $neededTypesLevel2 = array('title-1', 'title-2', 'title-3');

    /** @var array Types needed for the toc level 2b */
    private $neededTypesLevel2b = array('title-3');

    /** @var array Type container for the toc level 2b */
    private $containerTypeToc2b = 'title-2';

    /** @var array Types associated level for the TOC */
    private $displayTocLevel = array('title-1' => 0, 'title-2' => 1,
    'title-3' => 2, 'title-4' => 3, 'title-5' => 4
    );

    //FIXME there surely a better place for these constants
    /** the metadata key for icon */
    const COURSE_METADATA_ICON = 'image';

    /** the metadata key for duration */
    const COURSE_METADATA_DIFFICULTY = 'difficulty';

    /** the metadata key for aggregate rating */
    const COURSE_METADATA_AGGREGATE_RATING = 'aggregateRating';

    /** the metadata key for duration */
    const COURSE_METADATA_DURATION = 'duration';

    /** the metadata key for license */
    const COURSE_METADATA_LICENSE = 'license';

    /** the metadata key for license */
    const COURSE_METADATA_DESCRIPTION = 'description';

    /** @var ClaireApi */
    private $claireApi;

    /** @var CourseRepository */
    private $courseRepository;

    /** @var CategoryRepository */
    private $categoryRepository;


    /**
     * Setter for $claireApi
     *
     * @param ClaireApi $claireApi
     */
    public function setClaireApi (ClaireApi $claireApi)
    {
        $this->claireApi = $claireApi;
    }

    /**
     * Setter for $courseRepository
     *
     * @param CourseRepository $courseRepository
     */
    public function setCourseRepository (CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Setter for $categoryRepository
     *
     * @param CategoryRepository $categoryRepository
     */
    public function setCategoryRepository (CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * Get a course
     *
     * @param string $courseSlug The course's slug
     *
     * @return Course
     */
    public function getCourseBySlug($courseSlug)
    {
        $course = $this->courseRepository->findBySlug($courseSlug);
    }

    /**
     * Get a course and verify if it's in the correct category
     *
     * @param string $courseSlug   The course's slug
     * @param string $categorySlug The category's slug
     *
     * @return Course
     */
    public function getCourseBySlugByCategory($courseSlug, $categorySlug)
    {
        $course = $this->courseRepository->findBySlug($courseSlug);
        $category = $this->categoryRepository->findBySlug($categorySlug);

        $courseCategory = $course->getCategory();
        if (null !== $courseCategory && $courseCategory->getId() !== $category->getId()) {
            throw new HttpException(404);
        }

        return $course;

    }

    /**
     * Check if the course is in the category
     *
     * @param ApiResult $course   The course
     * @param ApiResult $category The category
     */
    public function checkCourseInCategory(ApiResult $course,
                    ApiResult $category)
    {
        if ($course['category']['id'] != $category['id']) {
            throw new NotFoundHttpException(
                'Unable to find this course in this category');
        }
    }

    /**
     * <p>
     * Returns the tags for the part
     * <ul>
     *     <li>If part don't have tags, return the parent's tags</li>
     * </ul>
     *</p>
     * @param array $courseTags The course's tags
     * @param array $partTags   The part's tags
     * @param array $parentTags The parent's tags
     *
     * @return array the tags
     */
    public function getPartTags($courseTags, $partTags = array(),
                    $parentTags = array())
    {
        $tags = array();
        if (0 !== count($partTags)) {
            $tags = $partTags;
        } elseif (0 !== count($parentTags)) {
            $tags = $parentTags;
        } elseif (0 !== count($courseTags)) {
            $tags = $courseTags;
        }
        return $tags;
    }

    /**
     * Return the value corresponding to the key in a multi dimensional array
     *
     * @param array $array The value
     * @param mixed $key   The key
     *
     * @return value | null
     */
    public function getMetadataValue(array $array, $key)
    {
        $value = null;

        foreach ($array as $subArray) {
            if ($subArray['key'] === $key) {
                $value = $metadata['value'];
                break;
            }
        }
        return $value;
    }

    /**
     * <p>
     * Get the formated metadatas
     *     <ul>
     *         <li>format the dates</li>
     *     </ul>
     * </p>
     * @param array $courseMetadatas The course metadatas
     *
     * @return array The formated metadatas
     */
    public function getFormatedCourseMetadatas($courseMetadatas)
    {
        /* Copy the key value of the course metadatas */
        foreach ($courseMetadatas as $metadata) {
            $key = $metadata['key'];
            $value = $metadata['value'];
            if (CourseService::COURSE_METADATA_DURATION === $key) {
                $value = new \DateInterval($value);
            }
            $formatedMetadatas[$key] = $value;
        }
        return $formatedMetadatas;
    }

    /**
     * <p>
     * Returns the formated metadatas
     *     <ul>
     *         <li>format the dates</li>
     *         <li>if icon, difficulty is not defined, set with the parent</li>
     *     </ul>
     * </p>
     *
     * @param array $courseMetadatas The course metadatas
     * @param array $partMetadatas   The part metadata
     * @param array $parentMetadatas The parent metadatas
     *
     * @return array The formated metadatas
     */
    public function getFormatedPartMetadatas($courseMetadatas, $partMetadatas,
                    $parentMetadatas = null)
    {
        $formatedMetadatas = array();
        /* Copy the key value of the course metadatas */
        foreach ($partMetadatas as $metadata) {
            $key = $metadata['key'];
            $value = $metadata['value'];
            if (CourseService::COURSE_METADATA_DURATION === $key) {
                $value = new \DateInterval($value);
            }
            $formatedMetadatas[$key] = $value;
        }
        return $formatedMetadatas;
    }

    /**
     * Check if the display level is correct for a course
     *
     * @param integer $displayLevel The display level
     *
     * @throws InvalidArgumentException
     */
    public function checkCourseDisplayLevelValidity($displayLevel)
    {
        if (0 > $displayLevel || $displayLevel > 2) {
            throw new \InvalidArgumentException(
                'Display level is not correct : Get '.$displayLevel.' Expected 0, 1, 2');
        }
    }

    /**
     * Check if the display level is correct for a part
     *
     * @param integer $displayLevel The display level
     *
     * @throws InvalidArgumentException
     */
    public function checkPartDisplayLevelValidity($displayLevel)
    {
        if (1 > $displayLevel || $displayLevel > 2) {
            throw new \InvalidArgumentException(
                'Display level is not correct : Get '.$displayLevel.' Expected 1, 2');
        }
    }

    /**
     * <p>
     * Format the content
     *     <ul>
     *         <li>Remove the title</li>
     *     </ul>
     * </p>
     *
     * @param html $content The html content
     *
     * @return html The formated html content
     */
    public function getFormatedContent($content)
    {
        /* Remove the title */
        if (!is_null($content)) {
            $content = preg_replace(self::PATTERN_HTML_PART_TITLE, '',
                $content, 1);
        }
        return $content;
    }

    /**
     * <p>Returns the pagination
     *     <ul>
     *         <li><b>previous<b> if exists, the previous page</li>
     *         <li><b>next</b> if exists, the next page</li>
     *     </ul>
     * <p>
     * @param array     $toc          The course toc
     * @param undefined $part         The current part
     * @param integer   $displayLevel The display level
     *
     * @return array The pagination
     */
    public function getPagination($toc, $part, $displayLevel)
    {
        /* Get the allowed types */
        $allowedTypes = $this->getAllowedTypes($displayLevel);

        $pagination = array();

        /* Get the toc key for the part */
        $tocKey = ArrayUtils::getKeyMultiDimensional($toc, 'id', $part['id']);
        if ($tocKey !== null) {
            $i = $tocKey;
            /* Get the previous */
            while (!array_key_exists('previous', $pagination) && $i > 0) {
                if (false !== array_search($toc[--$i]['type'], $allowedTypes)) {
                    $pagination['previous'] = $toc[$i];
                }
            }
            $i = $tocKey;
            /* Get the next */
            while (!array_key_exists('next', $pagination)
                && $i < (count($toc)) - 1) {
                if (false !== array_search($toc[++$i]['type'], $allowedTypes)) {
                    $pagination['next'] = $toc[$i];
                }
            }
        }
        return $pagination;
    }

    /**
     * Prepare the table of contents to display
     *
     * @param array   $toc          The original TOC
     * @param integer $displayLevel The display level of the course
     * @param course  $course       The course
     * @param part    $currentPart  The current part
     *
     * @return array The TOC to display
     */
    public function getDisplayToc($toc, $displayLevel, $course,
                    $currentPart = null)
    {
        $this->checkCourseDisplayLevelValidity($displayLevel);

        $neededTypes = array();

        /* If it's a course and display level = 2 */
        if (2 === $displayLevel && is_null($currentPart)) {
            $neededTypes = $this->neededTypesLevel2;

            /* If it's a part and display level = 2 */
        } else if (2 === $displayLevel && !is_null($currentPart)) {
            $neededTypes = $this->neededTypesLevel2b;
            /*
             * The toc needs to be filter (get only the
             * of the current part)
             */
            $toc = $this->filterToc2b($toc, $currentPartTitle);

        } else {
            $neededTypes = $this->neededTypesLevel1;
        }
        /* Process treatment on the toc */
        return $this->processToc($toc, $neededTypes, $course, $currentPart);
    }

    /**
     * Prepare the table of contents for the timeline
     *
     * @param array   $toc          The original TOC
     * @param integer $displayLevel The display level of the course
     * @param course  $course       The course
     * @param part    $currentPart  The current part
     *
     * @return array The TOC to display
     */
    public function formatTimeline($toc, $displayLevel, $course,
                    $currentPart = null)
    {
        $this->checkCourseDisplayLevelValidity($displayLevel);

        $neededTypes = array();

        /* If the display level is 2 */
        if (2 === $displayLevel) {
            $neededTypes = $this->neededTypesLevel2;
        } else {
            $neededTypes = $this->neededTypesLevel1;
        }

        return $this->processToc($toc, $neededTypes, $course, $currentPart);
    }

    /**
     * Process the toc for the render
     *
     * - puts only the needed parts
     * - checks if the part has been seen
     *
     * @param array  $toc          The original TOC
     * @param array  $allowedTypes The allowed types
     * @param course $course       The course
     * @param part   $currentPart  The current part
     *
     * @return array The TOC to display
     */
    private function processToc($toc, array $allowedTypes, $course,
                    $currentPart = null)
    {
        /* Initiate the toc to display */
        $formatedToc = array();

        /* variable isOver is used when the part has already been seen */
        $isOver = false;

        /* If it's a course, no part has already been seen */
        if (null === $currentPart) {
            $isOver = true;
        }

        $i = 0;

        $partLevel0 = null;
        $partLevel1 = null;
        $partLevel2 = null;

        /* Iterate on the TOC */
        foreach ($toc as $part) {
            if (array_search($part['type'], $allowedTypes) !== false) {
                $part['isOver'] = $isOver;

                /* Indicate the level in the TOC */
                $part['level'] = $this->displayTocLevel[$part['type']];

                switch ($part['level']) {
                    case 0:
                        $partLevel0 = $part;
                        $part['metadatas'] = $this
                            ->getFormatedPartMetadatas($course['metadatas'],
                                $part['metadatas']);
                        break;
                    case 1:
                        $partLevel1 = $part;
                        $part['metadatas'] = $this
                            ->getFormatedPartMetadatas($course['metadatas'],
                                $part['metadatas'], $partLevel0['metadatas']);
                        break;
                    case 2:
                        $partLevel2 = $part;
                        $part['metadatas'] = $this
                            ->getFormatedPartMetadatas($course['metadatas'],
                                $part['metadatas'], $partLevel1['metadatas']);
                        break;
                    case 3:
                        $part['metadatas'] = $this
                            ->getFormatedPartMetadatas($course['metadatas'],
                                $part['metadatas'], $partLevel2['metadatas']);
                        break;
                    default:
                        break;
                }

                $formatedToc[$i] = $part;

                /* If the part has already been seen */
                if ($part['id'] === $currentPart['id']) {
                    $isOver = true;
                }
                $i++;
            }
        }
        return $formatedToc;
    }

    /**
     * Filter the Table of Contents for level 2b
     * - only the sub parts of the asked part
     *
     * @param array  $toc         The original toc
     * @param string $currentPart The current part
     *
     * @return array The filtered toc
     */
    private function filterToc2b($toc, $currentPart)
    {
        /* the filtered toc to return */
        $filteredToc = array();

        /* True if the container part is find */
        $isFind = false;
        /* true if the container is passed */
        $isOver = false;

        $i = 0;
        $j = 0;

        /* Iterate on the toc */
        while ($i < count($toc) && $isOver === false) {
            $part = $toc[$i];

            /* If the part is the one asked */
            if ($part['type'] === $this->containerTypeToc2b) {
                if ($part['id'] === $currentPart['id']) {
                    $isFind = true;

                } else if ($isFind === true) {
                    $isOver = true;
                }
                /* if the part is a child of the container part */
            } else if ($isFind === true
                && array_search($part['type'], $this->neededTypesLevel2b)
                    !== false) {
                $toc2b[$j] = $part;
                $j++;
            }

            $i++;
        }
        return $filteredToc;
    }

    /**
     * Returns the allowed types for toc, timeline or pagination
     *
     * @param integer $displayLevel The display level
     * @param bool    $isCourse     <ul><li><b>false</b> if it's a part</li>
     *                                  <li><b>true</b> if it's a course</li></ul>
     *
     * @return array The allowed types
     */
    private function getAllowedTypes($displayLevel, bool $isCourse = null)
    {
        $this->checkCourseDisplayLevelValidity($displayLevel);

        $allowedTypes = array();

        /* If it's a course and display level = 2 */
        if (2 === $displayLevel && $isCourse) {
            $allowedTypes = $this->neededTypesLevel2;

            /* If it's a part and display level = 2 */
        } elseif (2 === $displayLevel && !$isCourse) {
            $allowedTypes = $this->neededTypesLevel2b;
        } else {
            $allowedTypes = $this->neededTypesLevel1;
        }
        return $allowedTypes;
    }

    /**
     * Getter for $neededTypesLevel1
     *
     * @return array the $neededTypesLevel1
     */
    private function getNeededTypesLevel1()
    {
        return $this->neededTypesLevel1;
    }

    /**
     * Getter for $neededTypesLevel2
     *
     * @return array the $neededTypesLevel2
     */
    private function getNeededTypesLevel2()
    {
        return $this->neededTypesLevel2;
    }

    /**
     * Getter for $neededTypesLevel2b
     *
     * @return array the $neededTypesLevel2b
     */
    private function getNeededTypesLevel2b()
    {
        return $this->neededTypesLevel2b;
    }

    /**
     * Getter for $displayTocLevel
     *
     * @return array the $displayTocLevel
     */
    private function getDisplayTocLevel()
    {
        return $this->displayTocLevel;
    }

    //FIXME Remove folowing

    //     /**
    //      * Get title type for one course
    //      *
    //      * @param array $slug Slug Course
    //      * @param array $toc  TOC
    //      *
    //      * @return array
    //      */
    //     public function getTitleType($slug = null, $toc = array())
    //     {
    //         $result = null;
    //         if (!empty($toc))
    //         {
    //             foreach($toc as $key => $element)
    //             {
    //                 if($element['slug'] == $slug)
    //                 {
    //                     $result = $toc[$key]['type'];
    //                     break;
    //                 }
    //             }
    //         }

    //         return $result;
    //     }

    //     /**
    //      * Restrict title for title 2
    //      *
    //      * @param array $course Course
    //      * @param array $toc    TOC
    //      *
    //      * @return array
    //      */
    //     public function restrictTocForTitle($course, $toc = array(), $titleRef = 'title-2')
    //     {
    //         $points = array(
    //             'course' => 0,
    //             'title-1' => 1,
    //             'title-2' => 2,
    //             'title-3' => 3,
    //         );

    //         $type = (is_null($course['type'])) ? 'course' : $course['type'];
    //         $result = $toc;
    //         $bool = false;
    //         if (!empty($toc) && $type == $titleRef)
    //         {
    //             $result = array();
    //             foreach($toc as $title)
    //             {
    //                 if ($type == 'course')
    //                 {
    //                     $bool = true;
    //                 }

    //                 if ($bool && $type != 'course' && $points[$type] >= $points[$title['type']])
    //                 {
    //                     $bool = false;
    //                 }

    //                 if ($bool && $points[$type] + 1 == $points[$title['type']])
    //                 {
    //                     $result[] = $title;
    //                 }

    //                 if ($title['slug'] == $course['slug'])
    //                 {
    //                     $bool = true;
    //                 }
    //             }
    //         }

    //         return $result;
    //     }

    //     /**
    //      * Set the prev and next index for a tutorial
    //      *
    //      * @param array $course The tutorial as array
    //      * @param array $toc    The toc as array
    //      *
    //      * @return array Tutorial
    //      */
    //     public function setPagination($course, $toc,
    //                     $restrictions = array('title-1'))
    //     {
    //         //         array_
    //         if (!empty($toc)) {
    //             foreach ($toc as $key => $element) {
    //                 if ($element['id'] == $course['id']) {
    //                     $tmp = $key - 1;
    //                     while ($tmp >= 0) {
    //                         if (!in_array($toc[$tmp]['type'], $restrictions)) {
    //                             $course['prev'] = $toc[$tmp];
    //                             break;
    //                         }
    //                         $tmp--;
    //                     }

    //                     $tmp = $key + 1;
    //                     $cpt = count($toc);
    //                     while ($tmp <= $cpt - 1) {
    //                         if (!in_array($toc[$tmp]['type'], $restrictions)) {
    //                             $course['next'] = $toc[$tmp];
    //                             break;
    //                         }
    //                         $tmp++;
    //                     }
    //                 }
    //             }
    //         }

    //         return $course;
    //     }
}
