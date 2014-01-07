<?php

namespace SimpleIT\ClaireAppBundle\Entity\Course\Part;

use SimpleIT\ApiResourcesBundle\Course\PartResource;
use SimpleIT\ClaireAppBundle\Entities\Course\Part\PartFactory;

/**
 * Class PartFactoryImpl
 *
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
            case self::TITLE_1:
                $part = new PartResource();
                $part->setSubtype(self::TITLE_1);
                break;
            case self::TITLE_2:
                $part = new PartResource();
                $part->setSubtype(self::TITLE_2);
                break;
            case self::TITLE_3:
                $part = new PartResource();
                $part->setSubtype(self::TITLE_3);
                break;
            default:
                throw new \InvalidArgumentException();
        }

        return $part;
    }

}
