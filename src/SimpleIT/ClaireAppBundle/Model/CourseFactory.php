<?php
namespace SimpleIT\ClaireAppBundle\Model;

use SimpleIT\ClaireAppBundle\Model\Course\Course;

/**
 * Class CourseFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseFactory
{
    /**
     * Create a course
     *
     * @param array $courseRessource
     *
     * @return Course
     */
    public static function create(array $courseRessource)
    {
        $course = new Course();
        if (isset($courseRessource['id'])) {
            $course->setId($courseRessource['id']);
        }
        if (isset($courseRessource['title'])) {
            $course->setTitle($courseRessource['title']);
        }
        if (isset($courseRessource['slug'])) {
            $course->setSlug($courseRessource['slug']);
        }
        if (isset($courseRessource['status'])) {
            $course->setStatus($courseRessource['status']);
        }
        if (isset($courseRessource['displayLevel'])) {
            $course->setDisplayLevel($courseRessource['displayLevel']);
        }
        if (isset($courseRessource['createdAt'])) {
            $course->setCreatedAt(new \DateTime($courseRessource['createdAt']));
        }
        if (isset($courseRessource['updatedAt'])) {
            $course->setUpdatedAt(new \DateTime($courseRessource['updatedAt']));
        }
        if (isset($courseRessource['category'])) {
            $category = CategoryFactory::create($courseRessource['category']);
            $course->setCategory($category);
        }
        return $course;
    }
}
