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
}
