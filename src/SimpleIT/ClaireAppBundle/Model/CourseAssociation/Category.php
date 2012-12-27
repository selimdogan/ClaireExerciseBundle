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
    private $title;

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
     * @var integer position
     */
    private $position;

    /**
     * @var array Tags
     */
    private $tags = array();

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
     * Getter for $title
     *
     * @return function the $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Setter for $title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
}
