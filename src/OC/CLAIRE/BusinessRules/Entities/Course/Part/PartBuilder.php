<?php

namespace OC\CLAIRE\BusinessRules\Entities\Course\Part;

use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class PartBuilder
{
    /**
     * @var PartResource
     */
    protected $part;

    public function withDescription($description)
    {
        $this->part->setDescription($description);

        return $this;
    }

    public function withDifficulty($difficulty)
    {
        $this->part->setDifficulty($difficulty);

        return $this;
    }

    public function withDuration($duration)
    {
        $this->part->setDuration($duration);

        return $this;
    }

    public function withTitle($title)
    {
        $this->part->setTitle($title);

        return $this;
    }

    public function build()
    {
        return $this->part;
    }
}
