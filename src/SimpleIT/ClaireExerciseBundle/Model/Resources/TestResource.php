<?php
namespace SimpleIT\ClaireExerciseResourceBundle\Model\Resources;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class TestResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TestResource
{
    /**
     * @const RESOURCE_NAME = 'Test'
     */
    const RESOURCE_NAME = 'Test';

    /**
     * @var integer $id Id of test
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "test", "list"})
     */
    private $id;

    /**
     * @var TestModelResource $testModel
     * @Serializer\Type("SimpleIT\ClaireExerciseResourceBundle\Model\Resources\TestModelResource")
     * @Serializer\Groups({"details", "test"})
     */
    private $testModel;

    /**
     * @var array $exercises
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "test"})
     */
    private $exercises;

    /**
     * Set exercises
     *
     * @param array $exercises
     */
    public function setExercises($exercises)
    {
        $this->exercises = $exercises;
    }

    /**
     * Get exercises
     *
     * @return array
     */
    public function getExercises()
    {
        return $this->exercises;
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
     * Set testModel
     *
     * @param TestModelResource $testModel
     */
    public function setTestModel($testModel)
    {
        $this->testModel = $testModel;
    }

    /**
     * Get testModel
     *
     * @return TestModelResource
     */
    public function getTestModel()
    {
        return $this->testModel;
    }
}
