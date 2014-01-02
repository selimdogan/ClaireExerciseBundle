<?php

namespace SimpleIT\ClaireAppBundle\Entity\Course\Part;

use OC\BusinessRules\Entities\Course\Part\PartFactory;
use SimpleIT\ApiResourcesBundle\Course\PartResource;

/**
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartFactoryImpl implements PartFactory
{
    /**
     * @return PartResource
     */
    public function make($subtype)
    {
        switch ($subtype) {
            case PartResource::TITLE_1:
                $part = new PartResource();
                $part->setSubtype(PartResource::TITLE_1);
                break;
            case PartResource::TITLE_2:
                $part = new PartResource();
                $part->setSubtype(PartResource::TITLE_2);
                break;
            case PartResource::TITLE_3:
                $part = new PartResource();
                $part->setSubtype(PartResource::TITLE_3);
                break;
            default:
                throw new \InvalidArgumentException();
        }

        return $part;
    }

}
