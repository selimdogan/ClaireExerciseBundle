<?php
namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\DomainKnowledge\CommonKnowledge;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class KnowledgeResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeResource
{
    /**
     * @const RESOURCE_NAME = 'Knowledge'
     */
    const RESOURCE_NAME = 'Knowledge';

    /**
     * @const MULTIPLE_CHOICE_QUESTION = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\MultipleChoiceQuestionResource'
     */
    const FORMULA_CLASS = 'SimpleIT\ClaireExerciseResourceBundle\Model\Resources\DomainKnowledge\Formula';

    /**
     * @var int $id Id of knowledge
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "knowledge_list"})
     * @Assert\Blank(groups={"create","editContent","edit", "appCreate"})
     */
    private $id;

    /**
     * @var string $type
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "list", "knowledge_list"})
     * @Assert\NotBlank(groups={"create", "appCreate"})
     * @Assert\Blank(groups={"editContent", "edit"})
     */
    private $type;

    /**
     * @var CommonKnowledge $content
     * @Serializer\Type("SimpleIT\ClaireExerciseResourceBundle\Model\Resources\DomainKnowledge\CommonKnowledge")
     * @Serializer\Groups({"details", "knowledge_list"})
     * @Assert\NotBlank(groups={"create","editContent"})
     * @Assert\Blank(groups={"appCreate"})
     * @Assert\Valid
     */
    private $content;

    /**
     * @var array $requiredKnowledges
     * @Serializer\Type("array")
     * @Serializer\Groups({"details"})
     * @Assert\NotNull(groups={"create"})
     * @Assert\Blank(groups={"editContent", "appCreate"})
     */
    private $requiredKnowledges;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "list", "knowledge_list"})
     * @Assert\Blank(groups={"create", "edit","editContent", "appCreate"})
     */
    private $author;

    /**
     * Set content
     *
     * @param CommonKnowledge $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return CommonKnowledge
     */
    public function getContent()
    {
        return $this->content;
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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set requiredKnowledges
     *
     * @param array $requiredKnowledges
     */
    public function setRequiredKnowledges($requiredKnowledges)
    {
        $this->requiredKnowledges = $requiredKnowledges;
    }

    /**
     * Get requiredKnowledges
     *
     * @return array
     */
    public function getRequiredKnowledges()
    {
        return $this->requiredKnowledges;
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

    /**
     * Return the item serialization class corresponding to the type
     *
     * @param string $type
     *
     * @return string
     * @throws \LogicException
     */
    static public function getClass($type)
    {
        switch ($type) {
            case CommonKnowledge::FORMULA:
                $class = self::FORMULA_CLASS;
                break;
            default:
                throw new \LogicException('Unknown type');
        }

        return $class;
    }
}
