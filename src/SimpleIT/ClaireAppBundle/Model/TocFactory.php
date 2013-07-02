<?php
namespace SimpleIT\ClaireAppBundle\Model;

/**
 * Class TocFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocFactory
{
    /**
     * Create a toc
     *
     * @param array $tocResource
     *
     * @return array The toc
     */
    public static function create(array $tocResource)
    {
        $toc = array();
        foreach ($tocResource as $partResource) {
            var_dump($tocResource);
            $part = PartFactory::create($partResource);
            if (isset($partResource['tags'])) {
                $tags = TagFactory::createCollection($partResource['tags']);
                $part->setTags($tags);
            }
            if (isset($partResource['metadatas'])) {
                $metadataResources = $partResource['metadatas'];
                $metadatas = MetadataFactory::createCourseMetadataCollection($metadataResources);
                $part->setMetadatas($metadatas);
            }
            $toc[] = $part;
        }
        return $toc;
    }
}
