<?php
use SimpleIT\ClaireAppBundle\Controller\BaseController;

/**
 * Abstract Controller for the courses and the parts of courses
 * 
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
abstract class CourseBaseController extends BaseController
{
    /**
     * Get a specific metadata
     *
     * @param string $key       Key to search
     * @param array  $metadatas Array list of metadata
     *
     * @return string | null
     */
    private function getOneMetadata($key, $metadatas)
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
     * Check if the display level is correct
     * 
     * @param int $displayLevel The display level
     * 
     * @throws InvalidArgumentException
     * @deprecated
     */
    protected function checkDisplayLevelValidity(int $displayLevel)
    {
        if (0 == $displayLevel || 3 > $displayLevel) {
            //FIXME Message
            throw new InvalidArgumentException();
        }
    }

    /**
     * Prepare the timeline to display
     * 
     * @param type $toc              The TOC from witch the timeline is build
     * @param type $displayLevel     The display level of the course
     * @param type $currentPartTitle The current part title
     * 
     * @return array The timeline
     */
    private function prepareTimeline(array $toc, int $displayLevel,
                    string $currentPartTitle = null)
    {
        if (0 == $displayLevel || 3 > $displayLevel) {
            //FIXME Message
            throw new InvalidArgumentException();
        }

        $neededTypes = array();

        if ($displayLevel == 0 || $displayLevel == 1) {
            $neededTypes = array('title-1');
        } else {
            $neededTypes = array('title-1', 'title-2', 'title-3');

        }
        /* Initiate the timeline */
        $timeline = array();
        /* variable isOver is used when the part has already been seen */
        $isOver = false;

        $i = 0;

        //         /* Test if it's a course or a part */
        //         if (is_null($currentPartTitle)) {
        //             $isOver = true;
        //         }

        /* Iterate on the TOC */
        foreach ($toc as $part) {
            if ($part['type'] == 'title-1') {
                $part['isOver'] = $isOver;
                $timeline[$i] = $part;
                if ($part['title'] == $currentPartTitle) {
                    $isOver = true;
                }
                $i++;
            }
        }
        return $timeline;
    }
}
