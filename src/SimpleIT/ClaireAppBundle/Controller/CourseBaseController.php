<?php
namespace SimpleIT\ClaireAppBundle\Controller;
use SimpleIT\ClaireAppBundle\Controller\BaseController;

/**
 * Abstract Controller for the courses and the parts of courses
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
abstract class CourseBaseController extends BaseController
{
    //TODO Put in Config
    /** @var array Types needed for the toc level 0 and 1 */
    private $neededTypesLevel1 = array('title-1');

    /** @var array Types needed for the toc level 2a */
    private $neededTypesLevel2 = array('title-1', 'title-2', 'title-3');

    /** @var array Types needed for the toc level 2b */
    private $neededTypesLevel2b = array('title-3');

    /** @var array Types associated level for the TOC */
    private $displayTocLevel = array('title-1' => 0, 'title-2' => 1,
    'title-3' => 2, 'title-4' => 3, 'title-5' => 4
    );

    /**
     * Get a specific metadata
     *
     * @param string $key       Key to search
     * @param array  $metadatas Array list of metadata
     *
     * @return string | null
     */
    protected function getOneMetadata($key, $metadatas)
    {
        $value = '';

        if (is_array($metadatas)) {
            foreach ($metadatas as $metadata) {
                if ($metadata['key'] == $key) {
                    $value = $metadata['value'];
                    break;
                }
            }
        }
        return $value;
    }

    /**
     * Check if the display level is correct for a course
     *
     * @param integer $displayLevel The display level
     *
     * @throws InvalidArgumentException
     */
    protected function checkCourseDisplayLevelValidity($displayLevel)
    {
        if (0 > $displayLevel || $displayLevel > 2) {
            //FIXME Message
            throw new \InvalidArgumentException();
        }
    }

    /**
     * Check if the display level is correct for a part
     *
     * @param integer $displayLevel The display level
     *
     * @throws InvalidArgumentException
     */
    protected function checkPartDisplayLevelValidity($displayLevel)
    {
        if (1 > $displayLevel || $displayLevel > 2) {
            //FIXME Message
            throw new \InvalidArgumentException();
        }
    }

    /**
     * Prepare the table of contents to display
     *
     * @param array   $toc              The original TOC
     * @param integer $displayLevel     The display level of the course
     * @param string  $currentPartTitle The current part title
     *
     * @return array The TOC to display
     */
    protected function getDisplayToc(array $toc, $displayLevel,
                    $currentPartTitle = null)
    {
        $this->checkCourseDisplayLevelValidity($displayLevel);
        $neededTypes = array();

        /* If */
        if (2 === $displayLevel && is_null($currentPartTitle)) {
            $neededTypes = $this->neededTypesLevel2;
        } else if (2 === $displayLevel && !is_null($currentPartTitle)) {
            $neededTypes = $this->neededTypesLevel2b;
            $toc = $this->filterToc2b($toc, $currentPartTitle);
        } else {
            $neededTypes = $this->neededTypesLevel1;
        }
        return $this->processToc($toc, $neededTypes, $currentPartTitle);
    }

    /**
     * Prepare the table of contents for the timeline
     *
     * @param array   $toc              The original TOC
     * @param integer $displayLevel     The display level of the course
     * @param string  $currentPartTitle The current part title
     *
     * @return array The TOC to display
     */
    protected function getTimeline(array $toc, $displayLevel,
                    string $currentPartTitle = null)
    {
        $this->checkCourseDisplayLevelValidity($displayLevel);
        $neededTypes = array();

        /* If */
        if (2 === $displayLevel) {
            $neededTypes = $this->neededTypesLevel2;
        } else {
            $neededTypes = $this->neededTypesLevel1;
        }
        //FIXME
        $neededTypes = $this->neededTypesLevel1;
        return $this->processToc($toc, $neededTypes, $currentPartTitle);
    }

    /**
     * Process the toc for the render
     *
     * @param array  $toc              The original TOC
     * @param array  $neededTypes      The allowed type
     * @param string $currentPartTitle The current part title
     *
     * @return array The TOC to display
     */
    private function processToc(array $toc, array $neededTypes,
                    $currentPartTitle = null)
    {
        /* Initiate the toc to display */
        $displayToc = array();

        /* variable isOver is used when the part has already been seen */
        $isOver = false;

        $i = 0;
        /* Iterate on the TOC */
        foreach ($toc as $part) {
            if (array_search($part['type'], $neededTypes) !== false) {
                $part['isOver'] = $isOver;
                /* Indicate the level in the TOC */
                $part['level'] = $this->displayTocLevel[$part['type']];

                $displayToc[$i] = $part;
                /* If the part has already been seen */
                if ($part['title'] == $currentPartTitle) {
                    $isOver = true;
                }
                $i++;
            }
        }
        return $displayToc;
    }

    private function filterToc2b($toc, $currentPartTitle)
    {
        $toc2b = array();

        $isFind = false;
        $isOver = false;
        $i = 0;
        $j = 0;

        while ($i < count($toc) && $isOver === false) {
            $part = $toc[$i];

            if ($part['type'] == 'title-2') {
                if ($part['title'] === $currentPartTitle) {
                    $isFind = true;

                } else if ($isFind === true) {
                    $isOver = true;
                }
            } else if ($isFind === true && $part['type'] === 'title-3') {
                $toc2b[$j] = $part;
                $j++;
            }

            $i++;
        }
        return $toc2b;
    }

    //     {
    //         $this->checkCourseDisplayLevelValidity($displayLevel);
    //         $neededTypes = array();

    //         if ($displayLevel == 0 || $displayLevel == 1) {
    //             $neededTypes = $this->neededTypesLevel01;
    //         } else {
    //             $neededTypes = $this->neededTypesLevel2;

    //         }

    //         /* Initiate the toc to display */
    //         $displayToc = array();

    //         /* variable isOver is used when the part has already been seen */
    //         $isOver = false;

    //         $i = 0;
    //         /* Iterate on the TOC */
    //         foreach ($toc as $part) {
    //             if (array_search($part['type'], $neededTypes) !== false) {
    //                 $part['isOver'] = $isOver;
    //                 /* Indicate the level in the TOC */
    //                 $part['level'] = $this->displayTocLevel[$part['type']];

    //                 $displayToc[$i] = $part;
    //                 /* If the part has already been seen */
    //                 if ($part['title'] == $currentPartTitle) {
    //                     $isOver = true;
    //                 }
    //                 $i++;
    //             }
    //         }
    //         return $displayToc;
    //     }

    //     private function prepareToc($toc, $displayLevel)
    //     {
    //         $displayToc = array();
    //         $i = 0;

    //         if ($displayLevel == 0 || $displayLevel == 1) {
    //             foreach ($toc as $part) {
    //                 if ($part['type'] == 'title-1') {
    //                     $displayToc[$i] = $part;
    //                     $i++;
    //                 }
    //             }
    //         } else {
    //             $displayTocLevel = array('title-1' => 0, 'title-2' => 1,
    //                     'title-3' => 2, 'title-4' => 3, 'title-5' => 4
    //             );
    //             foreach ($toc as $part) {
    //                 $part['level'] = $displayTocLevel[$part['type']];
    //                 $displayToc[$i] = $part;
    //                 $i++;
    //             }
    //         }
    //         return $displayToc;
    //     }
    //     /**
    //      *
    //      * @param type $toc
    //      * @param type $displayLevel
    //      * @param type $currentPartTitle
    //      * @return type
    //      */
    //     private function prepareTimeline($toc, $displayLevel, $currentPartTitle)
    //     {
    //         $neededTypes = array();
    //         if ($displayLevel == 0 || $displayLevel == 1) {
    //             $neededTypes = array('title-1');
    //         } else {
    //             $neededTypes = array('title-1', 'title-2', 'title-3');
    //         }
    //         $timeline = array();
    //         $i = 0;
    //         $isOver = false;
    //         if (!is_null($toc)) {
    //             foreach ($toc as $part) {
    //                 if ($part['type'] == 'title-1') {
    //                     $part['isOver'] = $isOver;
    //                     $timeline[$i] = $part;
    //                     if ($part['title'] == $currentPartTitle) {
    //                         $isOver = true;
    //                     }
    //                     $i++;
    //                 }
    //             }
    //         }
    //         return $timeline;
    //     }
}
