<?php
namespace SimpleIT\ClaireAppBundle\Services;

/**
 * CourseService
 */
class CourseService
{
    /**
     * Get title type for one course
     *
     * @param array $slug Slug Course
     * @param array $toc  TOC
     *
     * @return array
     */
    public function getTitleType($slug = null, $toc = array())
    {
        $result = null;
        if (!empty($toc))
        {
            foreach($toc as $key => $element)
            {
                if($element['slug'] == $slug)
                {
                    $result = $toc[$key]['type'];
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Restrict title for title 2
     *
     * @param array $course Course
     * @param array $toc    TOC
     *
     * @return array
     */
    public function restrictTocForTitle($course, $toc = array(), $titleRef = 'title-2')
    {
        $points = array(
            'course' => 0,
            'title-1' => 1,
            'title-2' => 2,
            'title-3' => 3,
        );

        $type = (is_null($course['type'])) ? 'course' : $course['type'];
        $result = $toc;
        $bool = false;
        if (!empty($toc) && $type == $titleRef)
        {
            $result = array();
            foreach($toc as $title)
            {
                if ($type == 'course')
                {
                    $bool = true;
                }

                if ($bool && $type != 'course' && $points[$type] >= $points[$title['type']])
                {
                    $bool = false;
                }

                if ($bool && $points[$type] + 1 == $points[$title['type']])
                {
                    $result[] = $title;
                }

                if ($title['slug'] == $course['slug'])
                {
                    $bool = true;
                }
            }
        }

        return $result;
    }

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
        if (!empty($toc))
        {
            foreach($toc as $key => $element)
            {
                if($element['id'] == $course['id'])
                {
                    $tmp = $key - 1;
                    while($tmp >= 0)
                    {
                        if ($toc[$tmp]['type'] != 'title-1')
                        {
                            $course['prev'] = $toc[$tmp];
                            break;
                        }
                        $tmp--;
                    }

                    $tmp = $key + 1;
                    $cpt = count($toc);
                    while($tmp <= $cpt-1)
                    {
                        if ($toc[$tmp]['type'] != 'title-1')
                        {
                            $course['next'] = $toc[$tmp];
                            break;
                        }
                        $tmp++;
                    }
                }
            }
        }

        return $course;
    }
}
