<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;

/**
 * Claire categories api
 */
class ClaireCategoriesApi extends ClaireApi
{
    const categories = '/categories/';
    /**
     * Get a course from slug
     *
     * @param string $slug Slug
     *
     * @return string Course at the html format
     */
    public function getCategories($chapterSlug, $rootSlug)
    {
        $course = $this->getTransportService()->get(self::categories.$rootSlug.'/'.$chapterSlug, array('Accept' => 'application/json'))->getContent();

        $course = json_decode($course, true);

        return $course;
    }

}
