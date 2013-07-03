<?php


namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use Doctrine\Common\Collections\Collection;
use SimpleIT\AppBundle\Repository\AppRepository;

/**
 * Class TagByPartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagByPartRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = 'courses/{courseIdentifier}/parts/{partIdentifier}/tags/';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource';

    /**
     * Find all tags of a part
     *
     * @param int | string $courseIdentifier Course id | slug
     * @param int | string $partIdentifier   Part id | slug
     *
     * @return Collection
     */
    public function findAll($courseIdentifier, $partIdentifier)
    {
        return parent::findAllResources(
            array(
                'courseIdentifier' => $courseIdentifier,
                'partIdentifier'   => $partIdentifier
            )
        );
    }
}
