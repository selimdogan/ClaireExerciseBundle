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
     * Create a course
     *
     * @param array $tocResource
     *
     * @return array The course
     */
    public static function create(array $tocResource)
    {
        $toc = array();
        foreach ($tocResource as $partResource) {
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
