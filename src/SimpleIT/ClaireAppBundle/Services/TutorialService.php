<?php
namespace SimpleIT\ClaireAppBundle\Services;

/**
 * TutorialService
 */
class TutorialService
{
    /**
     * Set the prev and next index for a tutorial
     *
     * @param array $tutorial The tutorial as array
     * @param array $toc      The toc as array
     *
     * @return array Tutorial
     */
    public function setPagination($tutorial, $toc)
    {
        $flatToc = $this->getFlatToc($toc);
        $prev = $toc;
        $found = false;

        foreach($flatToc as $element)
        {
            if($found)
            {
                $tutorial['next'] = $element;
                break;
            }

            if($element['id'] == $tutorial['id'])
            {
                $tutorial['prev'] = $prev;
                $found = true;
            }
            else
            {
                $prev = $element;
            }
        }

        return $tutorial;
    }

    /**
     * Get toc as a flat array
     *
     * @param array $toc ToC
     *
     * @return array
     */
    private function getFlatToc($toc)
    {
        static $flatToc = array();

        if (isset($toc['children']))
        {
            foreach($toc['children'] as $child)
            {
                $flatToc[] = $child;
                $this->getFlatToc($child);
            }
        }

        return $flatToc;
    }
}
