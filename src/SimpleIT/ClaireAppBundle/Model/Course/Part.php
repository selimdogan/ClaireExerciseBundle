<?php
namespace SimpleIT\ClaireAppBundle\Model\Course;
use SimpleIT\ClaireAppBundle\Model\Metadata;

use SimpleIT\ClaireAppBundle\Model\CourseAssociation\Tag;

/**
 * Class Part
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class Part
{
    /**
     * @const Title 1
     */
    const TYPE_TITLE_1 = 'title-1';

    /**
     * @const Title 2
     */
    const TYPE_TITLE_2 = 'title-2';

    /**
     * @const Title 3
     */
    const TYPE_TITLE_3 = 'title-3';

    /**
     * @const Title 4
     */
    const TYPE_TITLE_4 = 'title-4';

    /**
     * @const Title 5
     */
    const TYPE_TITLE_5 = 'title-5';

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
     * @var string course type
     */
    private $type;

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
     * @var array $tags
     */
    private $tags;

    /**
     * @var string $introduction
     */
    private $introduction;

    /**
     * @var boolean $over
     */
    private $over;

    /**
     * @var integer $tocLevel
     */
    private $tocLevel;

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
     * Getter for $title
     *
     * @return string the $title
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
     * @return string the $slug
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
     * Getter for $type
     *
     * @return string the $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter for $type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Getter for $createdAt
     *
     * @return DateTime the $createdAt
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
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for $updatedAt
     *
     * @return DateTime the $updatedAt
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
    public function setUpdatedAt(\DateTime $updatedAt)
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
     * Adder for $metadatas
     *
     * @param Metadata $metadata
     */
    public function addMetadata(Metadata $metadata)
    {
        $this->metadatas[] = $metadata;
    }

    /**
     * Getter for $tags
     *
     * @return array the $tags
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
     * adder for $tags
     *
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
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
     * Getter for $over
     *
     * @return boolean the $over
     */
    public function isOver()
    {
        return $this->over;
    }

    /**
     * Setter for $over
     *
     * @param boolean $over
     */
    public function setOver($over)
    {
        $this->over = $over;
    }

    /**
     * Getter for $tocLevel
     *
     * @return integer the $tocLevel
     */
    public function getTocLevel()
    {
        return $this->tocLevel;
    }

    /**
     * Setter for $tocLevel
     *
     * @param integer $tocLevel
     */
    public function setTocLevel($tocLevel)
    {
        $this->tocLevel = $tocLevel;
    }

}
