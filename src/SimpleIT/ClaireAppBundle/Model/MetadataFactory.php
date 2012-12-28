<?php
namespace SimpleIT\ClaireAppBundle\Model;
use SimpleIT\ClaireAppBundle\Model\Metadata;

/**
 * Class MetadataFactory
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class MetadataFactory
{
    /**
     * Create a metadata
     *
     * @param array $metadataResource The resource
     *
     * @return Metadata
     */
    public static function create(array $metadataResource)
    {
        $metadata = new Metadata();

        if (isset($metadataResource['key'])) {
            $metadata->setKey($metadataResource['key']);
        }
        if (isset($metadataResource['value'])) {
            $metadata->setValue($metadataResource['value']);
        }
        return $metadata;
    }

    /**
     * Create a collection of metadatas
     *
     * @param array $metadataResources The resources
     *
     * @return array The metadatas
     */
    public static function createCollection(array $metadataResources)
    {
        $metadatas = array();
        foreach ($metadataResources as $metadataResource) {
            $metadata = self::create($metadataResource);
            $metadatas[] = $metadata;
        }
        return $metadatas;
    }

    /**
     * <p>
     * Create a collection of course metadatas
     *     <ul>
     *         <li>format the dates</li>
     *     </ul>
     * </p>
     *
     * @param array $metadataResources The resources
     *
     * @return array The metadatas
     */
    public static function createCourseMetadataCollection(array $metadataResources)
    {
        $metadatas = array();
        foreach ($metadataResources as $metadataResource) {

            if (isset($metadataResource['key'])) {

                $value = $metadataResource['value'];

                if (Metadata::COURSE_METADATA_DURATION === $metadataResource['key']) {

                    try {
                        $value = new \DateInterval($value);
                        $metadatas[$metadataResource['key']] = $value;
                    } catch (\Exception $e) {
                        //Do nothing
                    }
                } else {
                    $metadatas[$metadataResource['key']] = $value;
                }
            }
        }
        return $metadatas;
    }
}
