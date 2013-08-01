<?php


namespace SimpleIT\ClaireAppBundle\Repository\AssociatedContent;

use SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatedCollection;
use SimpleIT\AppBundle\Annotation\Cache;

/**
 * Class TagByPartRepository
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TagRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/tags/{tagIdentifier}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\AssociatedContent\TagResource';

    /**
     * Find a list of tags
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return PaginatedCollection
     * @cache
     */
    public function findAll(CollectionInformation $collectionInformation = null)
    {
        return parent::findAllResources(
            array(),
            $collectionInformation
        );
    }

    /**
     * Find a tag
     *
     * @param int | string $tagIdentifier Tag id | slug
     *
     * @return TagResource
     * @cache
     */
    public function find($tagIdentifier)
    {
        return parent::findResource(array('tagIdentifier' => $tagIdentifier));
    }
}
