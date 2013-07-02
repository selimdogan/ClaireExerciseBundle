<?php
namespace SimpleIT\ClaireAppBundle\Services;
use SimpleIT\AppBundle\Model\ApiRequest;

use SimpleIT\ClaireAppBundle\Model\Course\Part;

use SimpleIT\ClaireAppBundle\Model\Metadata;

use SimpleIT\ClaireAppBundle\Model\MetadataFactory;

use SimpleIT\ClaireAppBundle\Model\PartFactory;

use SimpleIT\ClaireAppBundle\Model\TagFactory;

use SimpleIT\ClaireAppBundle\Repository\CourseAssociation\CategoryRepository;

use SimpleIT\ClaireAppBundle\Model\CourseFactory;

use SimpleIT\ClaireAppBundle\Api\ClaireApi;

use SimpleIT\ClaireAppBundle\Api;

use SimpleIT\ClaireAppBundle\Model\Course\Course;

use SimpleIT\ClaireAppBundle\Repository\Course\CourseRepository;

use SimpleIT\ClaireAppBundle\Repository\Course\PartRepository;

use SimpleIT\Utils\ArrayUtils;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\Validator\Constraints\All;

use SimpleIT\AppBundle\Model\ApiResult;
use SimpleIT\AppBundle\Model\ApiRequestOptions;

/**
 * CourseService
 */
class CourseService extends ClaireApi implements CourseServiceInterface
{
    /** regex for html part title = <h1>*</h1> */
    const PATTERN_HTML_PART_TITLE = '/<h1(.*)h1>/';

    /** @var ClaireApi */
    private $claireApi;

    /** @var CourseRepository */
    private $courseRepository;

    /** @var PartRepository */
    private $partRepository;

    /** @var CategoryRepository */
    private $categoryRepository;

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
     * Setter for $courseRepository
     *
     * @param CourseRepository $courseRepository
     */
    public function setCourseRepository(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Setter for $partRepository
     *
     * @param PartRepository $partRepository
     */
    public function setPartRepository(PartRepository $partRepository)
    {
        $this->partRepository = $partRepository;
    }

    /**
     * Setter for $categoryRepository
     *
     * @param CategoryRepository $categoryRepository
     */
    public function setCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get a course
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return Course
     */
    public function getCourse($courseIdentifier)
    {
        return $this->courseRepository->find($courseIdentifier);
    }

    /* **************************** *
     *                              *
     * ********** COURSE ********** *
     *                              *
     * **************************** */

    /**
     * Get a course and verify if it's in the correct category
     *
     * @param mixed $courseIdentifier   The course id | slug
     * @param mixed $categoryIdentifier The category id | slug
     *
     * @return Course
     * @deprecated
     */
    public function getCourseByCategory($courseIdentifier, $categoryIdentifier)
    {
        $course = $this->courseRepository->find($courseIdentifier);
        $category = $this->categoryRepository->find($categoryIdentifier);

        $courseCategory = $course->getCategory();
        if (null !== $courseCategory && $courseCategory->getId() !== $category->getId()) {
            throw new HttpException(404);
        }

        return $course;
    }

    /**
     * <p>
     *     Returns the course complementaries
     *     <ul>
     *         <li>metadatas</li>
     *         <li>tags</li>
     *         <li>introduction</li>
     *         <li>toc</li>
     *     </ul>
     * </p>
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param Course $course           The course
     *
     * @return Course
     * @deprecated
     */
    public function getCourseComplementaries($courseIdentifier, Course $course)
    {
        $courseComplementaries = $this->courseRepository
            ->findCourseComplementaries($courseIdentifier, $course);
        if (isset($courseComplementaries['metadatas'])) {
            $course->setMetadatas($courseComplementaries['metadatas']);
        }
        if (isset($courseComplementaries['tags'])) {
            $course->setTags($courseComplementaries['tags']);
        }
        if (isset($courseComplementaries['introduction'])) {
            $course->setIntroduction($courseComplementaries['introduction']);
        }
        if (isset($courseComplementaries['toc'])) {
            $course->setToc($courseComplementaries['toc']);
        }

        return $course;
    }

    /**
     * <p>
     *     Returns the course with the course complementaries
     *     <ul>
     *         <li>category</li>
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
     * @return Course
     */
    public function getCourseWithComplementaries($courseIdentifier, $categoryIdentifier)
    {
        return $this->courseRepository->findCourseWithComplementaries($courseIdentifier, $categoryIdentifier);
    }

    /**
     * Get courses
     *
     * @param ApiRequestOptions $options List option
     *
     * @return Collection
     */
    public function getCourses(ApiRequestOptions $options)
    {
        return $this->courseRepository->getAll($options);
    }


    /* **************************** *
     *                              *
     * *********** PART *********** *
     *                              *
     * **************************** */

    /**
     * Update a content of part
     *
     * @param mixed  $courseIdentifier The course id | slug
     * @param mixed  $partIdentifier   The part id | slug
     * @parma string $content          Content to save
     *
     * @return Part
     */
    public function updatePartContent($courseIdentifier, $partIdentifier, $content)
    {
        $part = $this->partRepository->updatePartContent($courseIdentifier, $partIdentifier, $content);

        return $part;
    }

    /**
     * Get a part
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return Part
     */
    public function getPart($courseIdentifier, $partIdentifier)
    {
        $part = $this->partRepository->findPart($courseIdentifier, $partIdentifier);

        return $part;
    }

    /**
     * <p>
     *     Returns the part with the part complementaries
     *     <ul>
     *         <li>metadatas</li>
     *         <li>tags</li>
     *         <li>introduction</li>
     *     </ul>
     * </p>
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     * @param Part  $part             The part
     *
     * @return Part
     * @deprecated
     */
    public function getPartComplementaries($courseIdentifier, $partIdentifier, Part $part)
    {
        $partComplementaries = $this->partRepository
            ->findPartComplementaries($courseIdentifier, $partIdentifier);

        if (isset($partComplementaries['metadatas'])) {
            $part->setMetadatas($partComplementaries['metadatas']);
        }
        if (isset($partComplementaries['tags'])) {
            $part->setTags($partComplementaries['tags']);
        }
        if (isset($partComplementaries['introduction'])) {
            $part->setIntroduction($partComplementaries['introduction']);
        }
        if (isset($partComplementaries['toc'])) {
            $part->setToc($partComplementaries['toc']);
        }

        return $part;
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
    public function getPartWithComplementaries($categoryIdentifier, $courseIdentifier, $partIdentifier)
    {
        return $this->partRepository->findPartWithComplementaries($categoryIdentifier, $courseIdentifier, $partIdentifier);
    }

    /**
     * Returns the html part introduction
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return string
     */
    public function getPartIntroduction($courseIdentifier, $partIdentifier)
    {
        return $this->partRepository->findIntroduction($courseIdentifier, $partIdentifier);
    }

    /**
     * Returns the html course content
     *
     * @param mixed $courseIdentifier The course id | slug
     *
     * @return Part
     */
    public function getCourseContent($courseIdentifier)
    {
        return $this->courseRepository
            ->findContent($courseIdentifier, ApiRequest::FORMAT_HTML);
    }

    /**
     * Returns the epub export course
     *
     * @param mixed $courseIdentifier
     */
    public function getCourseEpub($courseIdentifier)
    {
        return $this->courseRepository
            ->findContent($courseIdentifier, ApiRequest::FORMAT_EPUB);
    }

    /**
     * Returns the latex export course
     *
     * @param mixed $courseIdentifier
     */
    public function getCourseLatex($courseIdentifier)
    {
        return $this->courseRepository
            ->findContent($courseIdentifier, ApiRequest::FORMAT_LATEX);
    }

    /**
     * Returns the pdf export course
     *
     * @param mixed $courseIdentifier
     */
    public function getCoursePdf($courseIdentifier)
    {
        return $this->courseRepository
            ->findContent($courseIdentifier, ApiRequest::FORMAT_PDF);
    }

    /**
     * Returns the html part content
     *
     * @param mixed $courseIdentifier The course id | slug
     * @param mixed $partIdentifier   The part id | slug
     *
     * @return Part
     */
    public function getPartContent($courseIdentifier, $partIdentifier)
    {
        return $this->partRepository
            ->findContent($courseIdentifier, $partIdentifier, ApiRequest::FORMAT_HTML);
    }

    /* **************************** *
     *                              *
     * ********* SERVICES ********* *
     *                              *
     * **************************** */

    /**
     * Get the timeline
     *
     * @param Course $course      The course
     * @param Part   $currentPart The current part
     *
     * @return array The TOC to display
     */
    public function getTimeline(Course $course, Part $currentPart = null)
    {
        $neededTypes = array();

        /* If the display level is 2 */
        if (2 == $course->getDisplayLevel()) {
            $neededTypes = $this->getNeededTypesLevel2();
        } else {
            $neededTypes = $this->getNeededTypesLevel1();
        }

        return $this->processToc($course->getToc(), $neededTypes, $course, $currentPart);
    }

    /**
     * <p>
     * Returns the tags for the part
     * <ul>
     *     <li>If part don't have tags, return the parent's tags</li>
     * </ul>
     *</p>
     * @param Course $course     The course
     * @param Part   $part       The part
     * @param Part   $parentPart The parent's part
     *
     * @return array the tags
     */
    public function getPartTags(Course $course, Part $part, Part $parentPart = null)
    {
        $tags = array();
        if (!is_null($part) && 0 !== count($part->getTags())) {
            $tags = $part->getTags();
        } elseif (!is_null($parentPart) && 0 !== count($parentPart->getTags())) {
            $tags = $parentPart->getTags();
        } elseif (!is_null($course) && 0 !== count($course->getTags())) {
            $tags = $course->getTags();
        }
        return $tags;
    }

    /**
     * <p>
     * Returns the metadatas for the part
     * If part don't have metadatas, return the parent's metadatas
     * <ul>
     *     <li>image</li>
     *     <li>difficulty</li>
     * </ul>
     *</p>
     * @param Course $course     The course
     * @param Part   $part       The part
     * @param Part   $parentPart The parent's part
     *
     * @return array the tags
     */
    public function getPartMetadatas(Course $course, Part $part, Part $parentPart = null)
    {
        $metadatas = $part->getMetadatas();
        $parentMetadatas = null;
        if (!is_null($parentPart)) {
            $parentMetadatas = $parentPart->getMetadatas();
        }
        $courseMetadatas = null;
        if (!is_null($course)) {
            $courseMetadatas = $course->getMetadatas();
        }

        /* Get the image */
        if (!isset($metadatas[Metadata::COURSE_METADATA_IMAGE])) {
            $image = null;
            if (isset($parentMetadatas[Metadata::COURSE_METADATA_IMAGE])) {
                $image = $parentMetadatas[Metadata::COURSE_METADATA_IMAGE];
            } elseif (isset($courseMetadatas[Metadata::COURSE_METADATA_IMAGE])) {
                $image = $courseMetadatas[Metadata::COURSE_METADATA_IMAGE];
            }
            if (!is_null($image)) {
                $metadatas[Metadata::COURSE_METADATA_IMAGE] = $image;
            }
        }

        /* Get the difficulty */
        if (!isset($metadatas[Metadata::COURSE_METADATA_DIFFICULTY])) {
            $difficulty = null;
            if (isset($parentMetadatas[Metadata::COURSE_METADATA_DIFFICULTY])) {
                $difficulty = $parentMetadatas[Metadata::COURSE_METADATA_DIFFICULTY];
            } elseif (isset($courseMetadatas[Metadata::COURSE_METADATA_DIFFICULTY])) {
                $difficulty = $courseMetadatas[Metadata::COURSE_METADATA_DIFFICULTY];
            }
            if (!is_null($difficulty)) {
                $metadatas[Metadata::COURSE_METADATA_DIFFICULTY] = $difficulty;
            }
        }
        return $metadatas;
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
            $content = preg_replace(self::PATTERN_HTML_PART_TITLE, '', $content, 1);
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
     * @param Course  $course       The course
     * @param mixed   $currentPart  The current part
     * @param integer $displayLevel The display level
     *
     * @return array The pagination
     */
    public function getPagination(Course $course, $currentPart, $displayLevel)
    {
        /* Get the allowed types */
        $allowedTypes = $this->getAllowedTypesForPagination($displayLevel);

        $pagination = array();
        $find = false;
        $i = 0;
        $toc = $course->getToc();

        if (!is_null($currentPart)) {
            while (!$find && $i < (count($toc) - 1)) {
                $part = $toc[$i];
                if (false !== array_search($part->getType(), $allowedTypes)) {
                    if ($currentPart && $currentPart->getId() !== $part->getId()) {
                        $pagination['previous'] = $part;
                    } else {
                        $find = true;
                    }
                }
                $i++;
            }
        }
        $find = false;

        while (!$find && $i < (count($toc))) {

            $part = $toc[$i];

            if (false !== array_search($part->getType(), $allowedTypes)) {
                if (is_null($currentPart) || $currentPart->getId() !== $part->getId())
                {
                    $pagination['next'] = $part;
                    $find = true;
                }

            }
            $i++;
        }
        return $pagination;
    }

    /**
     * Prepare the table of contents to display
     *
     * @param course  $course       The course
     * @param integer $displayLevel The display level of the course
     * @param part    $currentPart  The current part
     *
     * @return array The TOC to display
     */
    public function getDisplayToc(Course $course, $displayLevel, Part $currentPart = null)
    {
        $toc = $course->getToc();

        $neededTypes = array();

        /* If it's a course and display level = 2 */
        if (2 == $displayLevel && is_null($currentPart)) {
            $neededTypes = $this->getNeededTypesLevel2();

            /* If it's a part and display level = 2 */
        } else if (2 == $displayLevel && !is_null($currentPart)) {
            $neededTypes = $this->getNeededTypesLevel2b();
            /*
             * The toc needs to be filter (get only the
             * of the current part)
             */
            $toc = $this->filterToc2b($toc, $currentPart);

        } else {
            $neededTypes = $this->getNeededTypesLevel1();
        }
        /* Process treatment on the toc */

        return $this->processToc($toc, $neededTypes, $course, $currentPart);
    }

    /**
     * Process the toc for the render
     *
     * - puts only the needed parts
     * - checks if the part has been seen
     *
     * @param array  $toc          The original toc
     * @param array  $allowedTypes The allowed types
     * @param Course $course       The course
     * @param part   $currentPart  The current part
     *
     * @return array The TOC to display
     */
    private function processToc(array $toc = array(), array $allowedTypes, Course $course,
                    Part $currentPart = null)
    {
        /* Initiate the toc to display */
        $formatedToc = array();

        /* variable isOver is used when the part has already been seen */
        $over = false;

        /* If it's a course, no part has already been seen */
        if (null === $currentPart) {
            $over = true;
        }

        $partLevel1 = null;
        $partLevel2 = null;
        $partLevel3 = null;

        /* Iterate on the TOC */
        foreach ($toc as $part) {

            /* If the part got an allowed type */
            if (array_search($part->getType(), $allowedTypes) !== false) {

                $part->setOver($over);

                switch ($part->getType()) {
                    case Part::TYPE_TITLE_1:
                        $part->setTocLevel(1);
                        $part->setMetadatas($this->getPartMetadatas($course, $part));
                        $partLevel1 = $part;
                        break;
                    case Part::TYPE_TITLE_2:
                        $part->setTocLevel(2);
                        $part->setMetadatas($this->getPartMetadatas($course, $part, $partLevel1));
                        $partLevel2 = $part;
                        break;
                    case Part::TYPE_TITLE_3:
                        $part->setTocLevel(3);
                        $partLevel3 = $part;
                        break;
                    case Part::TYPE_TITLE_4:
                        $part->setTocLevel(4);
                        break;
                    default:
                        break;
                }

                $formatedToc[] = $part;

                /* If the part has already been seen */
                if (null !== $currentPart && $part->getId() == $currentPart->getId()) {
                    $over = true;
                }
            }
        }

        return $formatedToc;
    }

    /**
     * Filter the Table of Contents for level 2b
     * - only the sub parts of the asked part
     *
     * @param array $toc         The original toc
     * @param Part  $currentPart The current part
     *
     * @return array The filtered toc
     */
    private function filterToc2b(array $toc, Part $currentPart)
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
        while ($i < count($toc) && !$isOver) {
            $part = $toc[$i];
            /* If the part is the one asked */
            if ($this->getParentPartTypeToc2b() === $part->getType()) {

                if ($part->getId() === $currentPart->getId()) {
                    $isFind = true;
                }
                elseif($isFind) {
                    $isOver = true;
                }

                /* if the part is a child of the container part */
            } elseif ($isFind === true
                && false !== array_search($part->getType(), $this->getNeededTypesLevel2b())) {

                $filteredToc[$j] = $part;
                $j++;
            }
            $i++;
        }

        return $filteredToc;
    }

    /**
     * Returns the allowed types for pagination
     *
     * @param integer $displayLevel The display level
     *
     * @return array The allowed types
     */
    private function getAllowedTypesForPagination($displayLevel)
    {
        $allowedTypes = array();

        if (2 == $displayLevel) {
            $allowedTypes = $this->getPaginationTypesLevel2();
        } elseif (1 == $displayLevel ) {
            $allowedTypes = $this->getPaginationTypesLevel1();
        }

        return $allowedTypes;
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
    private function getAllowedTypesForToc($displayLevel, boolean $isCourse = null)
    {
        $allowedTypes = array();

        /* If it's a course and display level = 2 */
        if (2 == $displayLevel && $isCourse) {
            $allowedTypes = $this->getNeededTypesLevel2();

            /* If it's a part and display level = 2 */
        } elseif (2 == $displayLevel && !$isCourse) {
            $allowedTypes = $this->getNeededTypesLevel2b();
        } else {
            $allowedTypes = $this->getNeededTypesLevel1();
        }
        return $allowedTypes;
    }

    //TODO Put in Config file

    /**
     * Getter for Types needed for the toc level 0 and 1
     *
     * @return array The types
     */
    private function getNeededTypesLevel1()
    {
        return array(Part::TYPE_TITLE_1);
    }

    /**
     * Getter for ypes needed for the toc level 2a
     *
     * @return array The types
     */
    private function getNeededTypesLevel2()
    {
        return array(Part::TYPE_TITLE_1, Part::TYPE_TITLE_2, Part::TYPE_TITLE_3);
    }

    /**
     * Getter for Types needed for the toc level 2b
     *
     * @return array The types
     */
    private function getNeededTypesLevel2b()
    {
        return array(Part::TYPE_TITLE_3);
    }

    /**
     * Getter for Types needed for the pagination level 1
     *
     * @return array The types
     */
    private function getPaginationTypesLevel1()
    {
        return array(Part::TYPE_TITLE_1);
    }

    /**
     * Getter for Types needed for the pagination level 2
     *
     * @return array The types
     */
    private function getPaginationTypesLevel2()
    {
        return array(Part::TYPE_TITLE_2, Part::TYPE_TITLE_3);
    }

    /**
     * Getter for parent part type in toc 2b
     *
     * @return array The type
     */
    private function getParentPartTypeToc2b()
    {
        return Part::TYPE_TITLE_2;
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
}
