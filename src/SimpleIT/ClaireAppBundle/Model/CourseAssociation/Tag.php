<?php
namespace SimpleIT\ClaireAppBundle\Model\CourseAssociation;
/**
 * Class Tag
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class Tag
{
    /**
     * @var integer $id Id of tag
     */
    private $id;

    /**
     * @var string tag name
     */
    private $name;

    /**
     * @var string tag slug
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
     * @var Category $category
     */
    private $category;

    /**
     * @var Collection $courses
     */
    private $courses;

    /**
     * @var Collection $associatedTags
     */
    private $associatedTags;

    /**
     * @var integer total tutorial
     */
    private $totalTutorial = 0;

    /**
     * Getter for $id
     *
     * @return integer the $id
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
     * @return string the $name
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
     * Getter for $category
     *
     * @return function the $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Setter for $category
     *
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
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

    /**
     * add a course
     *
     * @param Course $course
     */
    public function addCourse(Course $course)
    {
        $this->courses[] = $course;
    }

    /**
     * Getter for $associatedTags
     *
     * @return function the $associatedTags
     */
    public function getAssociatedTags()
    {
        return $this->associatedTags;
    }

    /**
     * Setter for $associatedTags
     *
     * @param array $associatedTags
     */
    public function setAssociatedTags($associatedTags)
    {
        $this->associatedTags = $associatedTags;
    }

    /**
     * add an associated Tag
     *
     * @param Course $course
     */
    public function addAssociatedTag(Tag $associatedTag)
    {
        $this->associatedTags[] = $associatedTag;
    }

    public function getTotalTutorial()
    {
        return $this->totalTutorial;
    }

    public function setTotalTutorial($totalTutorial)
    {
        $this->totalTutorial = $totalTutorial;
    }
}
