<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TestModelResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestModelResource
{
    /**
     * @const RESOURCE_NAME = 'Test Model'
     */
    const RESOURCE_NAME = 'Test Model';

    /**
     * @var integer $id Id of test
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "test_model", "test", "list"})
     * @Assert\Blank(groups={"edit", "create"})
     */
    private $id;

    /**
     * @var string $title
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "test_model", "test", "list"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $title;

    /**
     * @var integer $author Id of the author
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "test_model", "test", "list"})
     * @Assert\Blank(groups={"edit", "create"})
     */
    private $author;

    /**
     * @var array $ownerExerciseModels
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "test_model"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $ownerExerciseModels;

    /**
     * Set ownerExerciseModels
     *
     * @param array $ownerExerciseModels
     */
    public function setOwnerExerciseModels($ownerExerciseModels)
    {
        $this->ownerExerciseModels = $ownerExerciseModels;
    }

    /**
     * Get ownerExerciseModels
     *
     * @return array
     */
    public function getOwnerExerciseModels()
    {
        return $this->ownerExerciseModels;
    }

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param int $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
