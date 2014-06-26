<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems;

use JMS\Serializer\Annotation as Serializer;

/**

/**
 * Describes how the groups must be chosen and how to determine the group of
 * every object.
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ClassificationConstraints
{
    /**
     * The no-group objects are classified in a group which name is the value of
     * the metadata
     *
     * @const OWN = "own"
     */
    const OWN = "own";

    /**
     * The no-group objects are not used (can reduce the number of objects in
     * the exercise)
     *
     * @const REJECT = "reject"
     */
    const REJECT = "reject";

    /**
     * The no-group objects are classified in a group called "other"
     *
     * @const MISC = "misc"
     */
    const MISC = "misc";

    /**
     * @var string $other Indicate what to do with the values of the metadata
     *                 designated by the metaKey but not linked to a group.
     *                 Values in OWN, REJECT and MISC.
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $other;

    /**
     * @var array $metaKey An array of strings, the metaKeys used to determine
     *                     the groups.
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $metaKeys = array();

    /**
     * @var array $groups An array of Group
     * @Serializer\Type("array<SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\Group>")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    private $groups = array();

    /**
     * Get Other
     *
     * @return int
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Set other
     *
     * @param int $other
     */
    public function setOther($other)
    {
        $this->other = $other;
    }

    /**
     * Get metaKeys
     *
     * @return array
     */
    public function getMetaKeys()
    {
        return $this->metaKeys;
    }

    /**
     * Add metaKey
     *
     * @param string $metaKey
     */
    public function addMetaKey($metaKey)
    {
        $this->metaKeys[] = $metaKey;
    }

    /**
     * Set metaKeys
     *
     * @param array $metaKeys
     */
    public function setMetaKeys($metaKeys)
    {
        $this->metaKeys = $metaKeys;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set groups
     *
     * @param array $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * Add group
     *
     * @param Group $group
     */
    public function addGroup($group)
    {
        $this->groups[] = $group;
    }
}
