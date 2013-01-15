<?php
namespace SimpleIT\ClaireAppBundle\Model\Course;
use SimpleIT\ClaireAppBundle\Model\Metadata;

use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Category;

/**
 * Class Course
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class Course
{
    /**
     * @var integer $id Id of course
     */
    private $id;

    /**
     * @var string course title
     */
    private $title;

    /**
     * @var string course slug
     */
    private $slug;

    /**
     * @var string course status
     */
    private $status;

    /**
     * @var integer $displayLevel DisplayLevel of course
     */
    private $displayLevel;

    /**
     * @var DateTime $date
     */
    private $createdAt;

    /**
     * @var DateTime $date
     */
    private $updatedAt;

    /**
     * @var array $metadatas
     */
    private $metadatas = array();

    /**
     * @var Category $category
     */
    private $category;

    /**
     * @var array $tags
     */
    private $tags = array();

    /**
     * @var array $authors
     */
    private $authors = array();

    /**
     * @var string $introduction
     */
    private $introduction;

    /**
     * @var array $toc
     */
    private $toc;

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
     * Getter for $status
     *
     * @return function the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setter for $status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Getter for $displayLevel
     *
     * @return function the $displayLevel
     */
    public function getDisplayLevel()
    {
        return $this->displayLevel;
    }

    /**
     * Setter for $displayLevel
     *
     * @param integer $displayLevel
     */
    public function setDisplayLevel($displayLevel)
    {
        $this->displayLevel = $displayLevel;
    }

    /**
     * Getter for $createdAt
     *
     * @return function the $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Setter for $createdAt
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for $updatedAt
     *
     * @return function the $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Setter for $updatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for $metadatas
     *
     * @return Metadata the $metadatas
     */
    public function getMetadatas()
    {
        return $this->metadatas;
    }

    /**
     * Setter for $metadatas
     *
     * @param array $metadatas
     */
    public function setMetadatas(array $metadatas)
    {
        $this->metadatas = $metadatas;
    }

    /**
     * Setter for $metadatas
     *
     * @param Metadata $metadata
     */
    public function addMetadata(Metadata $metadata)
    {
        $this->metadatas[] = $metadata;
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
     * Getter for $authors
     *
     * @return function the $authors
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Setter for $authors
     *
     * @param array $authors
     */
    public function setAuthors(array $authors)
    {
        $this->authors = $authors;
    }

    /**
     * Getter for $introduction
     *
     * @return string the $introduction
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Setter for $introduction
     *
     * @param string $introduction
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    }

    /**
     * Getter for $toc
     *
     * @return function the $toc
     */
    public function getToc()
    {
        return $this->toc;
    }

    /**
     * Setter for $toc
     *
     * @param array $toc
     */
    public function setToc(array $toc)
    {
        $this->toc = $toc;
    }

}
