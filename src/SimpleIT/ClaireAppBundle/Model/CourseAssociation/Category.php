<?php
namespace SimpleIT\ClaireAppBundle\Model\CourseAssociation;
/**
 * Class Category
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class Category
{
    /**
     * @var integer $id Id of category
     */
    private $id;

    /**
     * @var string category title
     */
    private $name;

    /**
     * @var string category slug
     */
    private $slug;

    /**
     * @var string image url
     */
    private $image;

    /**
     * @var string description
     */
    private $description;

    /**
     * @var boolean state
     */
    private $state;

    /**
     * @var integer position
     */
    private $position;

    /**
     * @var array Tags
     */
    private $tags = array();

    /**
     * @var array Courses
     */
    private $courses = array();

    /**
     * Getter for $id
     *
     * @return function the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for $id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Getter for $name
     *
     * @return function the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter for $name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Getter for $slug
     *
     * @return function the $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Setter for $slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Getter for $image
     *
     * @return function the $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Setter for $image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Getter for $description
     *
     * @return function the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Setter for $description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Getter for $state
     *
     * @return function the $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Setter for $state
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Getter for $position
     *
     * @return function the $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Setter for $position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Getter for $tags
     *
     * @return function the $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Setter for $tags
     *
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Getter for $courses
     *
     * @return function the $courses
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Setter for $courses
     *
     * @param array $courses
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;
    }
}
