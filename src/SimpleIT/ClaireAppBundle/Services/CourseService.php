<?php
namespace SimpleIT\ClaireAppBundle\Services;

/**
 * CourseService
 */
class CourseService
{
    /**
     * Set the prev and next index for a tutorial
     *
     * @param array $course The tutorial as array
     * @param array $toc    The toc as array
     *
     * @return array Tutorial
     */
    public function setPagination($course, $toc)
    {
        $found = false;
        foreach($toc as $element)
        {
            if($found)
            {
                $course['next'] = $element;
                break;
            }

            if($element['id'] == $course['id'])
            {
                if (isset($prev))
                {
                    $course['prev'] = $prev;
                }
                $found = true;
            }
            else
            {
                $prev = $element;
            }
        }

        return $course;
    }
}
