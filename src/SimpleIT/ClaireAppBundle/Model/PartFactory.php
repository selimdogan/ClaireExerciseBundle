<?php
namespace SimpleIT\ClaireAppBundle\Model;
use SimpleIT\ClaireAppBundle\Model\Course\Part;

/**
 * Class PartFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class PartFactory
{
    /**
     * Create a part
     *
     * @param array $partRessource
     *
     * @return Part
     */
    public static function create(array $partRessource)
    {
        $part = new Part();
        if (isset($partRessource['id'])) {
            $part->setId($partRessource['id']);
        }
        if (isset($partRessource['title'])) {
            $part->setTitle($partRessource['title']);
        }
        if (isset($partRessource['slug'])) {
            $part->setSlug($partRessource['slug']);
        }
        if (isset($partRessource['subtype'])) {
            $part->setType($partRessource['subtype']);
        }
        if (isset($partRessource['createdAt'])) {
            $part->setCreatedAt(new \DateTime($partRessource['createdAt']));
        }
        if (isset($partRessource['updatedAt'])) {
            $part->setUpdatedAt(new \DateTime($partRessource['updatedAt']));
        }
        return $part;
    }
}
