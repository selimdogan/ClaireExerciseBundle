<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ModelDocument;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract class for the exercise models.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "exercise_model_type", map = {
 *    "group-items": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\Model",
 *    "pair-items": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\PairItems\Model",
 *    "order-items": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\Model",
 *    "multiple-choice": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\MultipleChoice\Model",
 *    "open-ended-question": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OpenEndedQuestion\Model"
 * })
 */
abstract class CommonModel
{
    /**
     * @const MULTIPLE_CHOICE = "multiple-choice"
     */
    const MULTIPLE_CHOICE = "multiple-choice";

    /**
     * @const GROUP_ITEMS = "group-items"
     */
    const GROUP_ITEMS = "group-items";

    /**
     * @const ORDER_ITEMS = "order-items"
     */
    const ORDER_ITEMS = "order-items";

    /**
     * @const PAIR_ITEMS = "pair-items"
     */
    const PAIR_ITEMS = "pair-items";

    /**
     * @const OPEN_ENDED_QUESTION = "open-ended-question"
     */
    const OPEN_ENDED_QUESTION = "open-ended-question";

    /**
     * @var string The wording
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage", "owner_exercise_model_list"})
     * @Assert\NotBlank(groups={"create"})
     */
    protected $wording;

    /**
     * @var array An array of ModelDocument
     * @Serializer\Type("array<SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ModelDocument>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $documents = array();

    /**
     * @var LocalFormula A LocalFormula
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $formula;

    /**
     * Get wording
     *
     * @return string
     */
    public function getWording()
    {
        return $this->wording;
    }

    /**
     * Set wording
     *
     * @param string $wording
     */
    public function setWording($wording)
    {
        $this->wording = $wording;
    }

    /**
     * Get documents
     *
     * @return array An array of ModelDocument
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Set documents
     *
     * @param array $documents An array of ModelDocument
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    /**
     * Add a document
     *
     * @param ModelDocument $document
     */
    public function addDocument(ModelDocument $document)
    {
        $this->documents[] = $document;
    }

    /**
     * Set formula
     *
     * @param \SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula $formula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
    }

    /**
     * Get formula
     *
     * @return \SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula
     */
    public function getFormula()
    {
        return $this->formula;
    }
}
