<?php

namespace SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel;

use Doctrine\Common\Collections\Collection;
use SimpleIT\ClaireExerciseBundle\Entity\User\User;

/**
 * Claire owner exercise model entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var ExerciseModel
     */
    private $exerciseModel;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var bool
     */
    private $public;

    /**
     * @var Collection
     */
    private $metadata;

    /**
     * Set exerciseModel
     *
     * @param ExerciseModel $exerciseModel
     */
    public function setExerciseModel($exerciseModel)
    {
        $this->exerciseModel = $exerciseModel;
    }

    /**
     * Get exerciseModel
     *
     * @return ExerciseModel
     */
    public function getExerciseModel()
    {
        return $this->exerciseModel;
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
     * Set owner
     *
     * @param User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set metadata
     *
     * @param Collection $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get metadata
     *
     * @return Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
