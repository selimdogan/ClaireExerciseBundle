<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;

/**
 * Claire categories api
 */
class ClaireCategoriesApi
{
    const categories = '/categories/';
    const tags = '/tags/';

    /**
     * Get categories
     */
    public function prepareCategories()
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
    public function prepareCategory($categorySlug)
    {
        $this->responses['category'] = $this->getTransportService()->get(
            self::categories.$categorySlug,
            array('Accept' => 'application/json')
        );
    }

        /**
     * Get a category from slug
     *
     * @param string $categorySlug Slug
     */
    public function getCategory($categorySlug)
    {
        $request = array();
        $request[ClaireApi::URL] = self::categories.$categorySlug;
        $request[ClaireApi::METHOD] = ClaireApi::METHOD_GET;
        $request[ClaireApi::FORMAT] = ClaireApi::FORMAT_JSON;

        return $request;
    }

    /**
     * Get tags from category slug
     *
     * @param string $categorySlug Slug
     */
    public function getTags($categorySlug)
    {
        $request = array();
        $request[ClaireApi::URL] = self::categories.$categorySlug.self::tags.'?sort=name asc';
        $request[ClaireApi::METHOD] = ClaireApi::METHOD_GET;
        $request[ClaireApi::FORMAT] = ClaireApi::FORMAT_JSON;

        return $request;
    }
    
    /**
     * Get tags from category slug
     *
     * @param string $categorySlug Slug
     */
    public function prepareTags($categorySlug)
    {
        $this->responses['tags'] = $this->getTransportService()->get(
            self::categories.$categorySlug.self::tags.'?sort=name asc',
            array('Accept' => 'application/json')
        );
    }

    /**
     * Get associated tags for this tag
     *
     * @param string $categorySlug Slug
     */
    public function prepareAssociatedTags($categorySlug, $tagSlug)
    {
        $this->responses['tags'] = $this->getTransportService()->get(
            self::categories.$categorySlug.self::tags.$tagSlug.'/associated-tags',
            array('Accept' => 'application/json')
        );
    }

    /**
     * Get single tag from category and tag slug
     *
     * @param string $categorySlug Slug
     */
    public function prepareTag($categorySlug, $tagSlug)
    {
        $this->responses['tag'] = $this->getTransportService()->get(
            self::categories.$categorySlug.self::tags.$tagSlug,
            array('Accept' => 'application/json')
        );
    }

}
