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
     * Get categories
     */
    public function getCategories()
    {
        $this->responses['categories'] = $this->getTransportService()->get(self::categories, array(
            'Accept' => 'application/json',
            'Range' => 'items=0-49'));
    }

    /**
     * Get a category from slug
     *
     * @param string $categorySlug Slug
     */
    public function getCategory($categorySlug)
    {
        $this->responses['category'] = $this->getTransportService()->get(
            self::categories.$categorySlug,
            array('Accept' => 'application/json')
        );
    }

    /**
     * Get tags from slug
     *
     * @param string $categorySlug Slug
     */
    public function getTags($categorySlug)
    {
        $this->responses['category'] = $this->getTransportService()->get(
            self::categories.$categorySlug.'/tags',
            array('Accept' => 'application/json')
        );
    }

}
