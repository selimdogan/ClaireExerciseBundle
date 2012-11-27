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
        if(!isset($tutorial['leftElement']['reference']['slug']))
        {
            $tutorial['prev'] = $this->getLeftSibling($tutorial, $toc);
        }
        else
        {
            $tutorial['prev'] = $tutorial['leftElement'];
        }

        if(!isset($tutorial['rightElement']['reference']['slug']))
        {
            $tutorial['next'] = $this->getRightSibling($tutorial, $toc);
        }
        else
        {
            $tutorial['next'] = $tutorial['rightElement'];
        }

        return $tutorial;
    }

    /**
     * Get the left sibling of a tutorial if needed
     *
     * @param array $tutorial The tutorial as array
     * @param array $toc      The toc as array
     *
     * @return array Tutorial
     */
    private function getLeftSibling($tutorial, $toc)
    {
        foreach($toc['children'] as $child)
        {
            if($child['id'] == $tutorial['id'])
            {
                return $toc;
            }

            foreach($child['children'] as $subchild)
            {
                if($subchild['id'] == $tutorial['id'])
                {
                    return $child;
                }
            }
        }
    }

    /**
     * Get the right sibling of a tutorial if needed
     *
     * @param array $tutorial The tutorial as array
     * @param array $toc      The toc as array
     *
     * @return array Tutorial
     */
    private function getRightSibling($tutorial, $toc)
    {
        $found = false;
        foreach($toc['children'] as $child)
        {
            if($found)
            {
                return $child;
            }

            foreach($child['children'] as $subchild)
            {
                if($subchild['id'] == $tutorial['id'])
                {
                    $found = true;
                }
            }
        }

        return null;
    }
}
