<?php
namespace SimpleIT\ClaireAppBundle\Api;

use Symfony\Component\HttpFoundation\Request;
use SimpleIT\ClaireAppBundle\Api\ClaireApi;

/**
 * Claire courses api
 */
class ClaireCoursesApi extends ClaireApi
{
    const branches = '/branches/';
    const elements = '/elements/';
    const courses = '/courses/';

    /**
     * Get a course from slug
     *
     * @param string $slug Slug
     *
     * @return string Course at the html format
     */
    public function prepareCourse($chapterSlug, $rootSlug)
    {
        $this->responses['tutorial'] = $this->getTransportService()->get(
            self::courses.$rootSlug.'/'.$chapterSlug,
            array('Accept' => 'application/json')
        );
    }

    /**
     * Get a toc from slug
     *
     * @param string $slug Slug
     *
     * @return string Toc at the html format
     */
    public function prepareToc($slug)
    {
        $this->responses['toc'] = $this->getTransportService()->get(
            self::courses.$slug.'/toc',
            array('Accept' => 'application/json')
        );
    }

    /**
     * Get a list of courses
     *
     * @return array
     */
    public function prepareCourses($parameters)
    {
        $filter = '?';
        foreach($parameters as $field => $value)
        {
            $filter .= $field.'='.$value.'&';
        }

        $this->responses['branches'] = $this->getTransportService()->get(self::branches.$filter, array(
            'Accept' => 'application/json',
            'Range' => 'items=0-49'));
    }

    /**
     * Create a new course
     *
     * @param array $course Data of the course to create
     *
     * @return array Data of the course
     */
    public function createCourse($course)
    {
        $course = $this->getTransportService()->post(self::elements, array('Accept' => 'application/json'), $course)->getContent();

        $course = json_decode($course, true);
        $data = array('rootElement' => $course['id'], 'reference' => $course['reference']['id']);

        /* Create branch */
        $this->getTransportService()->post(self::branches, array('Accept' => 'application/json'), $data);

        return $course;
    }

    /**
     * Update a course
     *
     * @param array $course Data of the course to update
     *
     * @return array Data of the course
     */
    public function updateCourse($course)
    {
        $branches = $this->getTransportService()->get(self::branches.'?reference='.$course['reference']['slug'])->getContent();
        $branches = json_decode($branches, true);
        $rootElement = $branches['branches'][0]['rootElement'];
        $branch = $branches['branches'][0];

        $data = array('rootElement' => $rootElement['id'], 'branch' => $branch['id'], 'content' => $course['content']);
        $course = $this->getTransportService()->put(self::elements.$course['id'], array('Accept' => 'application/json'), http_build_query($data))->getContent();
        $course = json_decode($course, true);

        return $course;
    }
}
